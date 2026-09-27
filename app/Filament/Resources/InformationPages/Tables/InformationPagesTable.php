<?php

namespace App\Filament\Resources\InformationPages\Tables;

use App\Enums\InformationCategory;
use App\Enums\PublishStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InformationPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Halaman')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('publish_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PublishStatus $state): string => match ($state) {
                        PublishStatus::Draft => 'gray',
                        PublishStatus::Published => 'success',
                        PublishStatus::Archived => 'danger',
                    }),
                TextColumn::make('serviceType.name')
                    ->label('Terkait Layanan')
                    ->badge()
                    ->color('gray')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('manager.name')
                    ->label('Pengelola')
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(InformationCategory::class),
                SelectFilter::make('publish_status')
                    ->label('Status Publikasi')
                    ->options(PublishStatus::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('publish')
                    ->label('Terbitkan')
                    ->icon('heroicon-o-globe-alt')
                    ->color('success')
                    ->visible(fn ($record) => $record->publish_status !== PublishStatus::Published)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'publish_status' => PublishStatus::Published,
                            'published_at' => now(),
                        ]);

                        Notification::make()->title('Halaman Diterbitkan')->success()->send();
                    }),
                Action::make('archive')
                    ->label('Arsipkan')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->visible(fn ($record) => $record->publish_status === PublishStatus::Published)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['publish_status' => PublishStatus::Archived]);
                        Notification::make()->title('Halaman Diarsipkan')->warning()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
