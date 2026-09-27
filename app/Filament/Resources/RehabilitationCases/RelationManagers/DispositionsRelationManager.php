<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DispositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'dispositions';

    protected static ?string $title = 'Riwayat Disposisi Kasus';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('from_user_id')
                    ->label('Disposisi Dari')
                    ->relationship('fromUser', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                Select::make('to_work_unit_id')
                    ->label('Unit Kerja Tujuan')
                    ->relationship('toWorkUnit', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('to_user_id')
                    ->label('Penerima Disposisi (Petugas)')
                    ->relationship('toUser', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('disposed_at')
                    ->label('Waktu Disposisi')
                    ->default(now())
                    ->required(),
                Textarea::make('instructions')
                    ->label('Instruksi / Arahan Tindak Lanjut')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('instructions')
            ->defaultSort('disposed_at', 'desc')
            ->columns([
                TextColumn::make('disposed_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('fromUser.name')
                    ->label('Dari')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('toUser.name')
                    ->label('Penerima')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('instructions')
                    ->label('Instruksi')
                    ->limit(60),
            ])
            ->headerActions([
                CreateAction::make()->label('Kirim Disposisi'),
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
