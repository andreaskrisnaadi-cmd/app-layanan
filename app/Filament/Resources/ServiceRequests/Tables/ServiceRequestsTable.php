<?php

namespace App\Filament\Resources\ServiceRequests\Tables;

use App\Enums\ApprovalDecision;
use App\Enums\ServiceRequestStatus;
use App\Models\District;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Tiket')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('serviceType.name')
                    ->label('Layanan')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('applicant_name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('village.district.name')
                    ->label('Kecamatan')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Desa')
                    ->toggleable(),
                IconColumn::make('is_priority')
                    ->label('Prioritas')
                    ->boolean()
                    ->trueIcon('heroicon-s-exclamation-triangle')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ServiceRequestStatus $state): string => match ($state) {
                        ServiceRequestStatus::Submitted => 'gray',
                        ServiceRequestStatus::DocumentCheck => 'info',
                        ServiceRequestStatus::RevisionRequested => 'danger',
                        ServiceRequestStatus::DataVerification,
                        ServiceRequestStatus::EligibilityVerification,
                        ServiceRequestStatus::Verification => 'primary',
                        ServiceRequestStatus::AwaitingApproval => 'warning',
                        ServiceRequestStatus::Issued,
                        ServiceRequestStatus::RecommendationIssued,
                        ServiceRequestStatus::MinistryApproved,
                        ServiceRequestStatus::Reactivated,
                        ServiceRequestStatus::Completed => 'success',
                        ServiceRequestStatus::Rejected,
                        ServiceRequestStatus::MinistryRejected => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->toggleable(),
                TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('service_type_id')
                    ->label('Jenis Layanan')
                    ->relationship('serviceType', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ServiceRequestStatus::class),
                SelectFilter::make('district')
                    ->label('Kecamatan')
                    ->options(District::pluck('name', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        filled($data['value'])
                            ? $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $data['value']))
                            : $query
                    ),
                TernaryFilter::make('is_priority')
                    ->label('Prioritas Medis'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('verifyDocuments')
                    ->label('Verifikasi Dokumen')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, [
                        ServiceRequestStatus::Submitted,
                        ServiceRequestStatus::DocumentCheck,
                    ]))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $handler = $record->serviceType?->handler?->value;
                        $newStatus = match ($handler) {
                            'dtsen' => ServiceRequestStatus::DataVerification,
                            'pbi' => ServiceRequestStatus::EligibilityVerification,
                            default => ServiceRequestStatus::Verification,
                        };

                        $record->update([
                            'status' => $newStatus,
                            'officer_id' => $record->officer_id ?? auth()->id(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => $newStatus->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pemeriksaan berkas selesai dan dinyatakan lengkap.',
                        ]);

                        Notification::make()
                            ->title('Berkas Terverifikasi')
                            ->body('Status tiket diperbarui ke tahap verifikasi data.')
                            ->success()
                            ->send();
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->visible(fn ($record) => ! in_array($record->status, [
                        ServiceRequestStatus::Completed,
                        ServiceRequestStatus::Rejected,
                        ServiceRequestStatus::RevisionRequested,
                    ]))
                    ->form([
                        Textarea::make('reason')
                            ->label('Catatan Kekurangan / Dokumen yang Perlu Diperbaiki')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ServiceRequestStatus::RevisionRequested,
                            'rejection_reason' => $data['reason'],
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ServiceRequestStatus::RevisionRequested->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Permintaan perbaikan berkas: ' . $data['reason'],
                        ]);

                        Notification::make()
                            ->title('Permintaan Revisi Terkirim')
                            ->body('Pemohon diminta melengkapi berkas.')
                            ->warning()
                            ->send();
                    }),
                Action::make('submitForApproval')
                    ->label('Ajukan Persetujuan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn ($record) => in_array($record->status, [
                        ServiceRequestStatus::DataVerification,
                        ServiceRequestStatus::EligibilityVerification,
                        ServiceRequestStatus::Verification,
                    ]))
                    ->form([
                        Textarea::make('verification_notes')
                            ->label('Catatan Hasil Verifikasi & Rekomendasi Petugas')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ServiceRequestStatus::AwaitingApproval,
                            'officer_notes' => $data['verification_notes'],
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ServiceRequestStatus::AwaitingApproval->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pengajuan diteruskan untuk persetujuan pimpinan / penandatangan: ' . $data['verification_notes'],
                        ]);

                        Notification::make()
                            ->title('Diteruskan untuk Persetujuan')
                            ->body('Status tiket dialihkan ke antrean persetujuan.')
                            ->warning()
                            ->send();
                    }),
                Action::make('approveRequest')
                    ->label('Setujui Pengajuan')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === ServiceRequestStatus::AwaitingApproval)
                    ->form([
                        Textarea::make('approval_notes')
                            ->label('Catatan / Arahan Penandatangan')
                            ->placeholder('Disetujui untuk diproses / diterbitkan.')
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $handler = $record->serviceType?->handler?->value;
                        $newStatus = match ($handler) {
                            'dtsen' => ServiceRequestStatus::Issued,
                            'pbi' => ServiceRequestStatus::RecommendationIssued,
                            default => ServiceRequestStatus::Completed,
                        };

                        $record->update([
                            'status' => $newStatus,
                            'completed_at' => $newStatus === ServiceRequestStatus::Completed ? now() : null,
                        ]);

                        $record->approvals()->create([
                            'step' => 1,
                            'approver_id' => auth()->id(),
                            'decision' => ApprovalDecision::Approved,
                            'notes' => $data['approval_notes'] ?? 'Disetujui oleh pejabat penandatangan.',
                            'decided_at' => now(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => $newStatus->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pengajuan disetujui: ' . ($data['approval_notes'] ?? 'Disetujui.'),
                        ]);

                        Notification::make()
                            ->title('Pengajuan Disetujui')
                            ->body('Status tiket diperbarui menjadi: ' . $newStatus->label())
                            ->success()
                            ->send();
                    }),
                Action::make('rejectRequest')
                    ->label('Tolak Pengajuan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => ! in_array($record->status, [
                        ServiceRequestStatus::Completed,
                        ServiceRequestStatus::Rejected,
                        ServiceRequestStatus::MinistryRejected,
                    ]))
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan Pengajuan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ServiceRequestStatus::Rejected,
                            'rejection_reason' => $data['rejection_reason'],
                            'completed_at' => now(),
                        ]);

                        if ($oldStatus === ServiceRequestStatus::AwaitingApproval) {
                            $record->approvals()->create([
                                'step' => 1,
                                'approver_id' => auth()->id(),
                                'decision' => ApprovalDecision::Returned,
                                'notes' => $data['rejection_reason'],
                                'decided_at' => now(),
                            ]);
                        }

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ServiceRequestStatus::Rejected->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pengajuan ditolak. Alasan: ' . $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Pengajuan Ditolak')
                            ->body('Keterangan penolakan dicatat.')
                            ->danger()
                            ->send();
                    }),
                Action::make('completeTicket')
                    ->label('Selesaikan Tiket')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => ! in_array($record->status, [
                        ServiceRequestStatus::Completed,
                        ServiceRequestStatus::Rejected,
                    ]))
                    ->form([
                        Textarea::make('service_result')
                            ->label('Hasil Layanan / Keterangan Selesai')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ServiceRequestStatus::Completed,
                            'service_result' => $data['service_result'],
                            'completed_at' => now(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ServiceRequestStatus::Completed->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Layanan telah diselesaikan: ' . $data['service_result'],
                        ]);

                        Notification::make()
                            ->title('Pengajuan Selesai')
                            ->body('Tiket layanan berhasil ditutup.')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
