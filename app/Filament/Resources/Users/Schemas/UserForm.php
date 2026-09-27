<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Village;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Akun')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText('Kosongkan jika tidak ingin mengubah password saat edit.'),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp / HP')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('nik')
                            ->label('NIK')
                            ->mask('9999999999999999')
                            ->length(16),
                        Toggle::make('is_active')
                            ->label('Akun Aktif')
                            ->default(true),
                    ]),
                Section::make('Peran & Wilayah Tugas')
                    ->columns(2)
                    ->schema([
                        Select::make('roles')
                            ->label('Peran (Role)')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                        Select::make('work_unit_id')
                            ->label('Unit Kerja / Bidang')
                            ->relationship('workUnit', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('district_id')
                            ->label('Kecamatan Penugasan')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Select::make('village_id')
                            ->label('Desa / Kelurahan Penugasan')
                            ->options(fn (Get $get) => filled($get('district_id'))
                                ? Village::where('district_id', $get('district_id'))->pluck('name', 'id')
                                : Village::pluck('name', 'id')
                            )
                            ->searchable(),
                    ]),
            ]);
    }
}
