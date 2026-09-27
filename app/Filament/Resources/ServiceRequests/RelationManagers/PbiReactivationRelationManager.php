<?php

namespace App\Filament\Resources\ServiceRequests\RelationManagers;

use App\Enums\MinistryDecision;
use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Models\NumberSequence;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PbiReactivationRelationManager extends RelationManager
{
    protected static string $relationship = 'pbiReactivation';

    protected static ?string $title = 'Data Reaktivasi PBI-JK';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('participant_name')
                    ->label('Nama Peserta BPJS')
                    ->required()
                    ->maxLength(100),
                TextInput::make('participant_nik')
                    ->label('NIK Peserta')
                    ->mask('9999999999999999')
                    ->length(16)
                    ->required(),
                TextInput::make('bpjs_card_number')
                    ->label('Nomor Kartu BPJS / KIS')
                    ->required()
                    ->maxLength(20),
                DatePicker::make('deactivated_date')
                    ->label('Tanggal Nonaktif (Perkiraan)'),
                Select::make('reason')
                    ->label('Alasan Reaktivasi')
                    ->options(PbiReason::class)
                    ->required(),
                TextInput::make('health_facility_name')
                    ->label('Nama Fasilitas Kesehatan (Faskes)')
                    ->placeholder('Contoh: RSUD Ngudi Waluyo'),
                TextInput::make('health_letter_number')
                    ->label('Nomor Surat Keterangan Medis'),
                TextInput::make('decile')
                    ->label('Desil DTSEN')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10),
                Textarea::make('eligibility_notes')
                    ->label('Catatan Verifikasi Kelayakan')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('recommendation_number')
                    ->label('Nomor Surat Rekomendasi')
                    ->placeholder('Diterbitkan otomatis saat rekomendasi disetujui'),
                Select::make('signer_id')
                    ->label('Pejabat Penandatangan')
                    ->relationship('signer', 'name'),
                DateTimePicker::make('proposed_to_ministry_at')
                    ->label('Waktu Usul ke Kemensos via SIKS-NG'),
                Select::make('ministry_decision')
                    ->label('Keputusan Kemensos')
                    ->options(MinistryDecision::class),
                DateTimePicker::make('ministry_decided_at')
                    ->label('Waktu Keputusan Kemensos'),
                DatePicker::make('reactivated_date')
                    ->label('Tanggal Aktif Kembali di BPJS'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('participant_name')
            ->columns([
                TextColumn::make('recommendation_number')
                    ->label('No. Rekomendasi')
                    ->badge()
                    ->color('info')
                    ->placeholder('Belum terbit'),
                TextColumn::make('participant_name')
                    ->label('Nama Peserta')
                    ->searchable(),
                TextColumn::make('bpjs_card_number')
                    ->label('No. BPJS')
                    ->searchable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->badge(),
                TextColumn::make('ministry_decision')
                    ->label('Keputusan Kemensos')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        MinistryDecision::Approved => 'success',
                        MinistryDecision::Rejected => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('reactivated_date')
                    ->label('Tgl Aktif')
                    ->date('d M Y')
                    ->placeholder('-'),
                IconColumn::make('is_stalled')
                    ->label('Tertahan')
                    ->boolean()
                    ->trueIcon('heroicon-s-clock')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('danger')
                    ->falseColor('gray'),
            ])
            ->headerActions([
                CreateAction::make()->label('Input Data Peserta PBI'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('issueRecommendation')
                    ->label('Terbitkan Rekomendasi')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->visible(fn ($record) => empty($record->recommendation_number))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $recomNumber = NumberSequence::getNextNumber('400.9.1/REK-PBI', now()->format('Ym'), 4);
                        $record->update([
                            'recommendation_number' => $recomNumber,
                            'recommendation_issued_at' => now(),
                            'signer_id' => auth()->id(),
                        ]);

                        $serviceRequest = $record->serviceRequest;
                        if ($serviceRequest) {
                            $oldStatus = $serviceRequest->status;
                            $serviceRequest->update(['status' => ServiceRequestStatus::RecommendationIssued]);
                            $serviceRequest->statusHistories()->create([
                                'from_status' => $oldStatus->value,
                                'to_status' => ServiceRequestStatus::RecommendationIssued->value,
                                'user_id' => auth()->id(),
                                'notes' => 'Rekomendasi Reaktivasi PBI-JK diterbitkan: ' . $recomNumber,
                            ]);
                        }

                        Notification::make()
                            ->title('Rekomendasi Diterbitkan')
                            ->body('Nomor Surat: ' . $recomNumber)
                            ->success()
                            ->send();
                    }),
                Action::make('proposeToMinistry')
                    ->label('Usulkan ke SIKS-NG')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn ($record) => ! empty($record->recommendation_number) && empty($record->proposed_to_ministry_at))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['proposed_to_ministry_at' => now()]);
                        $serviceRequest = $record->serviceRequest;
                        if ($serviceRequest) {
                            $oldStatus = $serviceRequest->status;
                            $serviceRequest->update(['status' => ServiceRequestStatus::ProposedToMinistry]);
                            $serviceRequest->statusHistories()->create([
                                'from_status' => $oldStatus->value,
                                'to_status' => ServiceRequestStatus::ProposedToMinistry->value,
                                'user_id' => auth()->id(),
                                'notes' => 'Usulan reaktivasi telah diinput ke SIKS-NG Kemensos.',
                            ]);
                        }

                        Notification::make()
                            ->title('Usulan Tercatat')
                            ->body('Status tiket diubah ke tahap pengusulan Kemensos.')
                            ->info()
                            ->send();
                    }),
                Action::make('recordMinistryDecision')
                    ->label('Catat Keputusan Kemensos')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->visible(fn ($record) => ! empty($record->proposed_to_ministry_at) && $record->ministry_decision === MinistryDecision::Pending)
                    ->form([
                        Select::make('ministry_decision')
                            ->label('Keputusan Kemensos')
                            ->options([
                                MinistryDecision::Approved->value => 'Disetujui Kemensos',
                                MinistryDecision::Rejected->value => 'Ditolak Kemensos',
                            ])
                            ->required(),
                        DateTimePicker::make('ministry_decided_at')
                            ->label('Waktu Penetapan Keputusan')
                            ->default(now())
                            ->required(),
                        Textarea::make('decision_notes')
                            ->label('Catatan Keputusan')
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $isApproved = $data['ministry_decision'] === MinistryDecision::Approved->value;
                        $record->update([
                            'ministry_decision' => $data['ministry_decision'],
                            'ministry_decided_at' => $data['ministry_decided_at'],
                            'is_stalled' => false,
                        ]);

                        $serviceRequest = $record->serviceRequest;
                        if ($serviceRequest) {
                            $oldStatus = $serviceRequest->status;
                            $targetStatus = $isApproved
                                ? ServiceRequestStatus::MinistryApproved
                                : ServiceRequestStatus::MinistryRejected;

                            $serviceRequest->update(['status' => $targetStatus]);
                            $serviceRequest->statusHistories()->create([
                                'from_status' => $oldStatus->value,
                                'to_status' => $targetStatus->value,
                                'user_id' => auth()->id(),
                                'notes' => 'Keputusan Kemensos dicatat: ' . ($isApproved ? 'Disetujui' : 'Ditolak') . '. ' . ($data['decision_notes'] ?? ''),
                            ]);
                        }

                        Notification::make()
                            ->title('Keputusan Kemensos Dicatat')
                            ->body($isApproved ? 'Usulan disetujui Kemensos.' : 'Usulan ditolak Kemensos.')
                            ->color($isApproved ? 'success' : 'danger')
                            ->send();
                    }),
                Action::make('confirmReactivation')
                    ->label('Konfirmasi Reaktivasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->ministry_decision === MinistryDecision::Approved && empty($record->reactivated_date))
                    ->form([
                        DatePicker::make('reactivated_date')
                            ->label('Tanggal Kartu Aktif Kembali')
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan Konfirmasi')
                            ->placeholder('Kartu telah dicek aktif di sistem BPJS.')
                            ->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'reactivated_date' => $data['reactivated_date'],
                        ]);

                        $serviceRequest = $record->serviceRequest;
                        if ($serviceRequest) {
                            $oldStatus = $serviceRequest->status;
                            $serviceRequest->update([
                                'status' => ServiceRequestStatus::Reactivated,
                                'completed_at' => now(),
                                'service_result' => 'Kepesertaan PBI-JK berhasil diaktifkan kembali per tanggal ' . $data['reactivated_date'],
                            ]);

                            $serviceRequest->statusHistories()->create([
                                'from_status' => $oldStatus->value,
                                'to_status' => ServiceRequestStatus::Reactivated->value,
                                'user_id' => auth()->id(),
                                'notes' => 'Kepesertaan aktif per ' . $data['reactivated_date'] . '. ' . ($data['notes'] ?? ''),
                            ]);
                        }

                        Notification::make()
                            ->title('Reaktivasi Berhasil')
                            ->body('Kepesertaan PBI-JK resmi aktif kembali.')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
