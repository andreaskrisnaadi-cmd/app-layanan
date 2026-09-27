<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Riwayat Status Kasus';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from_status')
                    ->label('Dari Status')
                    ->disabled(),
                TextInput::make('to_status')
                    ->label('Ke Status')
                    ->disabled(),
                Textarea::make('notes')
                    ->label('Catatan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('to_status')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                TextColumn::make('from_status')
                    ->label('Status Awal')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('to_status')
                    ->label('Status Baru')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('user.name')
                    ->label('Petugas / Sistem')
                    ->badge()
                    ->color('info'),
                TextColumn::make('notes')
                    ->label('Catatan Aktivitas')
                    ->wrap(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
