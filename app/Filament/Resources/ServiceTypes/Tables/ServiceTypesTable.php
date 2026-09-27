<?php

namespace App\Filament\Resources\ServiceTypes\Tables;

use App\Enums\ServiceHandler;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Layanan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('handler')
                    ->label('Handler')
                    ->badge()
                    ->color(fn (ServiceHandler $state): string => match ($state) {
                        ServiceHandler::Dtsen => 'warning',
                        ServiceHandler::Pbi => 'info',
                        ServiceHandler::Generic => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('sla_days')
                    ->label('Target SLA')
                    ->suffix(' hari')
                    ->sortable(),
                IconColumn::make('needs_assessment')
                    ->label('Assessment')
                    ->boolean(),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('handler')
                    ->label('Handler')
                    ->options(ServiceHandler::class),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
