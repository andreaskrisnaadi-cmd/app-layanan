<?php

namespace App\Filament\Resources\Complaints\RelationManagers;

use App\Enums\ComplaintAttachmentType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Lampiran Foto & Bukti Pengaduan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Jenis Lampiran')
                    ->options(ComplaintAttachmentType::class)
                    ->default(ComplaintAttachmentType::Photo)
                    ->required(),
                FileUpload::make('file_path')
                    ->label('Berkas Bukti / Foto')
                    ->disk('public')
                    ->directory('complaint_attachments')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_path')
            ->columns([
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('file_path')
                    ->label('Path Berkas')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Waktu Unggah')
                    ->dateTime('d M Y H:i'),
            ])
            ->headerActions([
                CreateAction::make()->label('Unggah Bukti Baru'),
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
