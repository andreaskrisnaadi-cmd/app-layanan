<?php

namespace App\Filament\Resources\DtsenPurposes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DtsenPurposeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                TextInput::make('name')
                    ->label('Nama Tujuan / Keperluan')
                    ->required()
                    ->maxLength(100),
                TextInput::make('max_decile')
                    ->label('Batas Desil Maksimal')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->default(5)
                    ->helperText('Hanya pemohon dengan desil ≤ nilai ini yang berhak terbit SK')
                    ->required(),
                TextInput::make('validity_days')
                    ->label('Masa Berlaku (Hari)')
                    ->numeric()
                    ->default(180)
                    ->suffix('hari')
                    ->helperText('Contoh: 180 hari (6 bulan) atau 365 hari (1 tahun)')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
