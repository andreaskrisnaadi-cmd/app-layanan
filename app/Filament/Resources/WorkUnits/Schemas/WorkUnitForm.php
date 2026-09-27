<?php

namespace App\Filament\Resources\WorkUnits\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Unit Kerja')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                TextInput::make('name')
                    ->label('Nama Unit Kerja')
                    ->required()
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('Deskripsi / Tugas Pokok')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
