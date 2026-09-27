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
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessments';

    protected static ?string $title = 'Hasil Assessment Kebutuhan Klien';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('assessment_date')
                    ->label('Tanggal Pelaksanaan Assessment')
                    ->default(now())
                    ->required(),
                Select::make('officer_id')
                    ->label('Petugas Asesor')
                    ->relationship('officer', 'name')
                    ->default(fn () => auth()->id())
                    ->required(),
                Toggle::make('needs_referral')
                    ->label('Memerlukan Rujukan ke Lembaga Luar')
                    ->default(false),
                Textarea::make('result')
                    ->label('Kondisi & Hasil Temuan Assessment')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('service_needs')
                    ->label('Kebutuhan Pelayanan Klien')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('recommendation')
                    ->label('Rekomendasi Rencana Penanganan')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('result')
            ->defaultSort('assessment_date', 'desc')
            ->columns([
                TextColumn::make('assessment_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Asesor')
                    ->badge()
                    ->color('info'),
                TextColumn::make('result')
                    ->label('Hasil Temuan')
                    ->limit(50),
                TextColumn::make('recommendation')
                    ->label('Rekomendasi')
                    ->limit(50),
                IconColumn::make('needs_referral')
                    ->label('Perlu Rujukan')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Hasil Assessment'),
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
