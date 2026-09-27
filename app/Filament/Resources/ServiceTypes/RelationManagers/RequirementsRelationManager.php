<?php

namespace App\Filament\Resources\ServiceTypes\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Nama Persyaratan / Dokumen')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Toggle::make('is_mandatory')
                    ->label('Wajib Diunggah')
                    ->default(true),
                \Filament\Forms\Components\TextInput::make('allowed_mimes')
                    ->label('Format Diizinkan')
                    ->placeholder('pdf,jpg,jpeg,png')
                    ->default('pdf,jpg,jpeg,png'),
                \Filament\Forms\Components\TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('No')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Dokumen Persyaratan')
                    ->searchable(),
                \Filament\Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean(),
                TextColumn::make('allowed_mimes')
                    ->label('Format File')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
