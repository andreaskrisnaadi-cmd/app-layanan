<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tiket & Status')
                    ->columns(3)
                    ->schema([
                        TextInput::make('request_number')
                            ->label('Nomor Tiket')
                            ->placeholder('Otomatis saat disimpan')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label('Status Pengajuan')
                            ->options(ServiceRequestStatus::class)
                            ->default(ServiceRequestStatus::Submitted)
                            ->required(),
                        Toggle::make('is_priority')
                            ->label('Prioritas / Darurat Medis')
                            ->default(false)
                            ->helperText('Tandai jika kondisi darurat atau membutuhkan tindakan segera'),
                        Select::make('service_type_id')
                            ->label('Jenis Layanan')
                            ->relationship('serviceType', 'name')
                            ->required()
                            ->live()
                            ->columnSpanFull(),
                    ]),

                Section::make('Data Pemohon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('applicant_name')
                            ->label('Nama Lengkap Pemohon')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('applicant_nik')
                            ->label('NIK Pemohon')
                            ->mask('9999999999999999')
                            ->length(16)
                            ->required(),
                        TextInput::make('family_card_number')
                            ->label('Nomor Kartu Keluarga (KK)')
                            ->mask('9999999999999999')
                            ->length(16)
                            ->required(),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp / HP')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Select::make('village_id')
                            ->label('Desa / Kelurahan Domisili')
                            ->relationship('village', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('address')
                            ->label('Alamat Lengkap (RT/RW/Dusun)')
                            ->required()
                            ->rows(2),
                    ]),

                Section::make('Penugasan & Unit Kerja')
                    ->columns(2)
                    ->schema([
                        Select::make('work_unit_id')
                            ->label('Unit Kerja Penanggung Jawab')
                            ->relationship('workUnit', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('officer_id')
                            ->label('Petugas Verifikator / Pelaksana')
                            ->relationship('officer', 'name')
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('submitted_at')
                            ->label('Waktu Diajukan')
                            ->default(now()),
                        DateTimePicker::make('completed_at')
                            ->label('Waktu Selesai'),
                    ]),

                Section::make('Catatan Proses & Verifikasi')
                    ->columns(2)
                    ->schema([
                        Textarea::make('verification_result')
                            ->label('Hasil Verifikasi Dokumen & Data')
                            ->rows(3),
                        Textarea::make('officer_notes')
                            ->label('Catatan Petugas')
                            ->rows(3),
                        Textarea::make('assessment_notes')
                            ->label('Catatan Assessment Lapangan (Bila Ada)')
                            ->rows(3),
                        Textarea::make('service_result')
                            ->label('Hasil Layanan')
                            ->rows(3),
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan / Revisi (Jika Ditolak/Diminta Perbaikan)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
