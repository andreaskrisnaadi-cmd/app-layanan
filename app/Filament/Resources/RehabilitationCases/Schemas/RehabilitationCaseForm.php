<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\RehabilitationCaseStatus;
use App\Enums\RehabilitationHandlingType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RehabilitationCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kasus')
                    ->columns(3)
                    ->schema([
                        TextInput::make('case_number')
                            ->label('Nomor Kasus')
                            ->placeholder('Otomatis saat disimpan')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label('Status Kasus')
                            ->options(RehabilitationCaseStatus::class)
                            ->default(RehabilitationCaseStatus::Received)
                            ->required(),
                        Select::make('handling_type')
                            ->label('Jenis Penanganan')
                            ->options(RehabilitationHandlingType::class)
                            ->default(RehabilitationHandlingType::DirectService)
                            ->required(),
                        Select::make('officer_id')
                            ->label('Petugas Pendamping')
                            ->relationship('officer', 'name')
                            ->searchable()
                            ->preload(),
                        DateTimePicker::make('received_at')
                            ->label('Waktu Penerimaan Kasus')
                            ->default(now()),
                    ]),

                Section::make('Identitas Klien')
                    ->schema([
                        Select::make('client_id')
                            ->label('Klien Terdaftar')
                            ->relationship('client', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name}" . ($record->category ? " ({$record->category->name})" : ''))
                            ->searchable(['name', 'nik'])
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Lengkap Klien')
                                    ->required()
                                    ->maxLength(100),
                                Select::make('client_category_id')
                                    ->label('Kategori PPKS')
                                    ->relationship('category', 'name')
                                    ->required(),
                                TextInput::make('nik')
                                    ->label('NIK (Bila Tersedia)')
                                    ->mask('9999999999999999')
                                    ->length(16),
                                Select::make('gender')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir'),
                                Select::make('village_id')
                                    ->label('Desa / Kelurahan')
                                    ->relationship('village', 'name')
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('phone')
                                    ->label('No. Telepon / Pendamping'),
                                Textarea::make('address')
                                    ->label('Alamat / Lokasi Ditemukan')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Hasil & Penutupan Kasus')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('closed_at')
                            ->label('Waktu Ditutup / Selesai'),
                        Textarea::make('handling_result')
                            ->label('Hasil Penanganan Akhir & Evaluasi Kasus')
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Wajib diisi sebelum kasus dapat dinyatakan selesai/ditutup.'),
                    ]),
            ]);
    }
}
