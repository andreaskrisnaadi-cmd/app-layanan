<?php

namespace App\Filament\Resources\InformationPages\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DownloadableFormsRelationManager extends RelationManager
{
    protected static string $relationship = 'forms';

    protected static ?string $title = 'Formulir Unduhan & Dokumen Template';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Formulir / Template')
                    ->required()
                    ->maxLength(255),
                TextInput::make('version')
                    ->label('Versi Formulir')
                    ->placeholder('Contoh: v1.0, 2026')
                    ->default('v1.0')
                    ->maxLength(50),
                FileUpload::make('file_path')
                    ->label('File Formulir (PDF / Word / Excel)')
                    ->disk('public')
                    ->directory('forms')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->maxSize(10240)
                    ->required(),
                Toggle::make('is_current')
                    ->label('Formulir Aktif Saat Ini')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Formulir')
                    ->searchable(),
                TextColumn::make('version')
                    ->label('Versi')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_current')
                    ->label('Status Aktif')
                    ->boolean(),
                TextColumn::make('file_path')
                    ->label('Berkas')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : '-')
                    ->color('primary'),
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Formulir'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
