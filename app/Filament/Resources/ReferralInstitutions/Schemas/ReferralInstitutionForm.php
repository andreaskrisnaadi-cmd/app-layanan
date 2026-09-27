<?php

namespace App\Filament\Resources\ReferralInstitutions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReferralInstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lembaga')
                    ->required()
                    ->maxLength(150),
                TextInput::make('type')
                    ->label('Tipe / Jenis Lembaga')
                    ->placeholder('Contoh: RSUD, Panti Sosial, Balai Rehabilitasi, Yayasan')
                    ->maxLength(50),
                TextInput::make('contact')
                    ->label('Kontak / Penanggung Jawab')
                    ->placeholder('Telepon / No. HP / Nama PJ')
                    ->maxLength(100),
                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->rows(3)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}
