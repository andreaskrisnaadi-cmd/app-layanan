<?php

namespace App\Filament\Resources\ServiceRequests\RelationManagers;

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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DtsenCertificateRelationManager extends RelationManager
{
    protected static string $relationship = 'dtsenCertificate';

    protected static ?string $title = 'Data Surat Keterangan DTSEN';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dtsen_purpose_id')
                    ->label('Tujuan Penggunaan')
                    ->relationship('purpose', 'name')
                    ->required()
                    ->live(),
                TextInput::make('purpose_description')
                    ->label('Keterangan Keperluan')
                    ->placeholder('Contoh: Syarat SPMB Jalur Afirmasi SMAN 1'),
                TextInput::make('subject_name')
                    ->label('Nama Yang Diterangkan')
                    ->required()
                    ->maxLength(100),
                TextInput::make('subject_nik')
                    ->label('NIK Yang Diterangkan')
                    ->mask('9999999999999999')
                    ->length(16)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get) {
                        if (strlen($state ?? '') === 16 && $get('dtsen_purpose_id')) {
                            $duplicate = \App\Models\DtsenCertificate::where('subject_nik', $state)
                                ->where('dtsen_purpose_id', $get('dtsen_purpose_id'))
                                ->where('valid_until', '>=', now())
                                ->whereHas('serviceRequest', fn ($q) =>
                                    $q->whereNotIn('status', [ServiceRequestStatus::Rejected->value, 'cancelled'])
                                )
                                ->exists();

                            if ($duplicate) {
                                Notification::make()
                                    ->title('Peringatan Duplikasi')
                                    ->body('Terdapat SK DTSEN yang masih berlaku untuk NIK dan tujuan yang sama.')
                                    ->warning()
                                    ->persistent()
                                    ->send();
                            }
                        }
                    }),
                TextInput::make('relationship_to_applicant')
                    ->label('Hubungan dengan Pemohon')
                    ->placeholder('Anak Kandung / Diri Sendiri / Anggota Keluarga')
                    ->required(),
                Toggle::make('is_registered')
                    ->label('Terdaftar di Data SIKS-NG')
                    ->default(true),
                TextInput::make('decile')
                    ->label('Peringkat Desil (1-10)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),
                DateTimePicker::make('checked_at')
                    ->label('Waktu Pengecekan SIKS-NG')
                    ->default(now()),
                Select::make('checker_id')
                    ->label('Petugas Pengecek')
                    ->relationship('checker', 'name')
                    ->default(fn () => auth()->id()),
                TextInput::make('certificate_number')
                    ->label('Nomor Surat')
                    ->placeholder('Diterbitkan otomatis saat disetujui'),
                DatePicker::make('valid_until')
                    ->label('Masa Berlaku Hingga'),
                Select::make('signer_id')
                    ->label('Pejabat Penandatangan')
                    ->relationship('signer', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('certificate_number')
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('Nomor SK')
                    ->badge()
                    ->color('success')
                    ->placeholder('Belum terbit')
                    ->searchable(),
                TextColumn::make('subject_name')
                    ->label('Nama Tertera')
                    ->searchable(),
                TextColumn::make('purpose.name')
                    ->label('Tujuan')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('decile')
                    ->label('Desil')
                    ->badge()
                    ->color('warning'),
                IconColumn::make('is_registered')
                    ->label('SIKS-NG')
                    ->boolean(),
                TextColumn::make('valid_until')
                    ->label('Berlaku S/D')
                    ->date('d M Y'),
            ])
            ->headerActions([
                CreateAction::make()->label('Input Data Cek SIKS-NG'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('issueCertificate')
                    ->label('Terbitkan SK')
                    ->icon('heroicon-o-document-check')
                    ->color('success')
                    ->visible(fn ($record) => empty($record->certificate_number))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $certNumber = NumberSequence::getNextNumber('400.9.1/SK-DTSEN', now()->format('Ym'), 4);
                        $validDays = $record->purpose?->validity_days ?? 180;

                        $record->update([
                            'certificate_number' => $certNumber,
                            'issued_at' => now(),
                            'valid_until' => now()->addDays($validDays),
                            'verification_code' => Str::upper(Str::random(12)),
                            'signer_id' => auth()->id(),
                        ]);

                        $serviceRequest = $record->serviceRequest;
                        if ($serviceRequest) {
                            $oldStatus = $serviceRequest->status;
                            $serviceRequest->update([
                                'status' => ServiceRequestStatus::Issued,
                            ]);

                            $serviceRequest->statusHistories()->create([
                                'from_status' => $oldStatus->value,
                                'to_status' => ServiceRequestStatus::Issued->value,
                                'user_id' => auth()->id(),
                                'notes' => 'Surat Keterangan DTSEN diterbitkan dengan nomor: ' . $certNumber,
                            ]);
                        }

                        Notification::make()
                            ->title('SK DTSEN Diterbitkan')
                            ->body('Nomor Surat: ' . $certNumber)
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
