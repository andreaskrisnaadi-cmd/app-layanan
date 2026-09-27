<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pelapor & Lokasi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reporter_name')
                            ->label('Nama Pelapor')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('reporter_phone')
                            ->label('Nomor WhatsApp / HP Pelapor')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Select::make('village_id')
                            ->label('Desa / Kelurahan Kejadian')
                            ->relationship('village', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Textarea::make('location_detail')
                            ->label('Alamat / Titik Lokasi Masalah Sosial')
                            ->required()
                            ->rows(2),
                    ]),

                Section::make('Isi Pengaduan & Informasi Masalah')
                    ->columns(3)
                    ->schema([
                        TextInput::make('complaint_number')
                            ->label('Nomor Pengaduan')
                            ->placeholder('Otomatis saat disimpan')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('complaint_category_id')
                            ->label('Kategori Pengaduan')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->label('Status Pengaduan')
                            ->options(ComplaintStatus::class)
                            ->default(ComplaintStatus::Received)
                            ->required(),
                        DateTimePicker::make('reported_at')
                            ->label('Waktu Laporan Diterima')
                            ->default(now()),
                        Select::make('officer_id')
                            ->label('Petugas Tindak Lanjut')
                            ->relationship('officer', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('duplicate_of_id')
                            ->label('Laporan Induk (Jika Duplikat)')
                            ->relationship('duplicateOf', 'complaint_number')
                            ->searchable(),
                        Textarea::make('description')
                            ->label('Deskripsi / Kronologi Permasalahan Sosial')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Verifikasi & Penanganan Petugas')
                    ->columns(2)
                    ->schema([
                        Textarea::make('verification_result')
                            ->label('Hasil Verifikasi Awal Petugas')
                            ->rows(3),
                        Textarea::make('action_taken')
                            ->label('Tindakan / Hasil Penanganan')
                            ->rows(3),
                        DateTimePicker::make('resolved_at')
                            ->label('Waktu Selesai Ditangani'),
                    ]),
            ]);
    }
}
