<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

use App\Enums\ServiceHandler;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Layanan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Layanan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        TextInput::make('name')
                            ->label('Nama Layanan')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('Contoh: Administrasi / Bantuan / Perlindungan'),
                        Select::make('handler')
                            ->label('Alur Penanganan (Handler)')
                            ->options(ServiceHandler::class)
                            ->required(),
                        TextInput::make('sla_days')
                            ->label('Target SLA')
                            ->numeric()
                            ->default(3)
                            ->suffix('hari'),
                        Toggle::make('needs_assessment')
                            ->label('Perlu Assessment Lapangan')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Textarea::make('description')
                            ->label('Deskripsi Layanan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
