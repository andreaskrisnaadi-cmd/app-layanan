<?php

namespace App\Filament\Resources\ClientCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori Klien')
                    ->placeholder('Contoh: Lansia Terlantar, ODGJ, Penyandang Disabilitas, Anak Berhadapan Hukum')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
            ]);
    }
}
