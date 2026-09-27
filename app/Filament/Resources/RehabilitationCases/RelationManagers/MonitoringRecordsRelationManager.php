<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MonitoringRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'monitoringRecords';

    protected static ?string $title = 'Catatan Monitoring & Perkembangan Klien';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('monitoring_date')
                    ->label('Tanggal Monitoring')
                    ->default(now())
                    ->required(),
                Select::make('officer_id')
                    ->label('Petugas Monitoring')
                    ->relationship('officer', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                Select::make('referral_id')
                    ->label('Terkait Rujukan Tertentu (Opsional)')
                    ->relationship('referral', 'referral_number')
                    ->searchable()
                    ->preload(),
                Textarea::make('progress')
                    ->label('Perkembangan Kondisi Klien')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('result_notes')
                    ->label('Catatan Hasil & Rekomendasi Lanjutan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('progress')
            ->defaultSort('monitoring_date', 'desc')
            ->columns([
                TextColumn::make('monitoring_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->badge()
                    ->color('info'),
                TextColumn::make('referral.referral_number')
                    ->label('Rujukan')
                    ->placeholder('-'),
                TextColumn::make('progress')
                    ->label('Perkembangan')
                    ->limit(60),
                TextColumn::make('result_notes')
                    ->label('Catatan')
                    ->limit(40),
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Catatan Monitoring'),
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
