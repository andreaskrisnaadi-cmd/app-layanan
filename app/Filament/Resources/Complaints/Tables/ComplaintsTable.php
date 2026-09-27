<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\District;
use App\Models\User;
use App\Models\WorkUnit;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('complaint_number')
                    ->label('No. Pengaduan')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('reporter_name')
                    ->label('Pelapor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reporter_phone')
                    ->label('No. HP')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('village.district.name')
                    ->label('Kecamatan')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Desa')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ComplaintStatus $state): string => match ($state) {
                        ComplaintStatus::Received => 'gray',
                        ComplaintStatus::Verification => 'info',
                        ComplaintStatus::ClarificationRequested => 'warning',
                        ComplaintStatus::Dispatched,
                        ComplaintStatus::InHandling => 'primary',
                        ComplaintStatus::Resolved => 'success',
                        ComplaintStatus::Duplicate => 'secondary',
                        ComplaintStatus::Invalid => 'danger',
                    }),
                TextColumn::make('officer.name')
                    ->label('Petugas PJ')
                    ->toggleable(),
                TextColumn::make('reported_at')
                    ->label('Dilaporkan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('complaint_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ComplaintStatus::class),
                SelectFilter::make('district')
                    ->label('Kecamatan')
                    ->options(District::pluck('name', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        filled($data['value'])
                            ? $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $data['value']))
                            : $query
                    ),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('verify')
                    ->label('Verifikasi Awal')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === ComplaintStatus::Received)
                    ->form([
                        Textarea::make('verification_result')
                            ->label('Hasil Verifikasi Awal Lapangan / Telaah')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ComplaintStatus::Verification,
                            'verification_result' => $data['verification_result'],
                            'officer_id' => $record->officer_id ?? auth()->id(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::Verification->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Verifikasi pengaduan: ' . $data['verification_result'],
                        ]);

                        Notification::make()->title('Pengaduan Terverifikasi')->success()->send();
                    }),
                Action::make('requestClarification')
                    ->label('Minta Klarifikasi')
                    ->icon('heroicon-o-question-mark-circle')
                    ->color('warning')
                    ->visible(fn ($record) => in_array($record->status, [
                        ComplaintStatus::Received,
                        ComplaintStatus::Verification,
                    ]))
                    ->form([
                        Textarea::make('clarification_notes')
                            ->label('Poin Klarifikasi yang Diperlukan dari Pelapor')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ComplaintStatus::ClarificationRequested,
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::ClarificationRequested->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Permintaan klarifikasi pelapor: ' . $data['clarification_notes'],
                        ]);

                        Notification::make()->title('Permintaan Klarifikasi Tercatat')->warning()->send();
                    }),
                Action::make('dispatch')
                    ->label('Disposisikan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn ($record) => in_array($record->status, [
                        ComplaintStatus::Received,
                        ComplaintStatus::Verification,
                        ComplaintStatus::ClarificationRequested,
                    ]))
                    ->form([
                        Select::make('to_user_id')
                            ->label('Petugas Penerima Disposisi')
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('to_work_unit_id')
                            ->label('Unit Kerja')
                            ->options(WorkUnit::pluck('name', 'id'))
                            ->searchable(),
                        Textarea::make('instructions')
                            ->label('Arahan / Instruksi Penanganan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->dispositions()->create([
                            'from_user_id' => auth()->id(),
                            'to_user_id' => $data['to_user_id'],
                            'to_work_unit_id' => $data['to_work_unit_id'] ?? null,
                            'instructions' => $data['instructions'],
                            'disposed_at' => now(),
                        ]);

                        $record->update([
                            'status' => ComplaintStatus::Dispatched,
                            'officer_id' => $data['to_user_id'],
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::Dispatched->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Disposisi pengaduan kepada petugas. Instruksi: ' . $data['instructions'],
                        ]);

                        Notification::make()->title('Pengaduan Berhasil Didisposisikan')->success()->send();
                    }),
                Action::make('startHandling')
                    ->label('Mulai Penanganan')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === ComplaintStatus::Dispatched)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ComplaintStatus::InHandling,
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::InHandling->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Petugas memulai penanganan langsung atas pengaduan.',
                        ]);

                        Notification::make()->title('Penanganan Pengaduan Dimulai')->primary()->send();
                    }),
                Action::make('resolve')
                    ->label('Selesaikan Aduan')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, [
                        ComplaintStatus::Dispatched,
                        ComplaintStatus::InHandling,
                    ]))
                    ->form([
                        Textarea::make('action_taken')
                            ->label('Tindakan yang Telah Dilakukan & Hasil Akhir')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ComplaintStatus::Resolved,
                            'action_taken' => $data['action_taken'],
                            'resolved_at' => now(),
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::Resolved->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Pengaduan diselesaikan: ' . $data['action_taken'],
                        ]);

                        Notification::make()->title('Pengaduan Telah Diselesaikan')->success()->send();
                    }),
                Action::make('markDuplicate')
                    ->label('Tandai Duplikat')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->visible(fn ($record) => ! in_array($record->status, [
                        ComplaintStatus::Resolved,
                        ComplaintStatus::Duplicate,
                    ]))
                    ->form([
                        Select::make('duplicate_of_id')
                            ->label('Pilih Pengaduan Induk')
                            ->options(fn ($record) => Complaint::when($record, fn ($q) => $q->where('id', '!=', $record->id))->pluck('complaint_number', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $oldStatus = $record->status;
                        $record->update([
                            'status' => ComplaintStatus::Duplicate,
                            'duplicate_of_id' => $data['duplicate_of_id'],
                        ]);

                        $record->statusHistories()->create([
                            'from_status' => $oldStatus->value,
                            'to_status' => ComplaintStatus::Duplicate->value,
                            'user_id' => auth()->id(),
                            'notes' => 'Ditandai sebagai duplikat pengaduan #' . $data['duplicate_of_id'],
                        ]);

                        Notification::make()->title('Ditandai Sebagai Duplikat')->info()->send();
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
