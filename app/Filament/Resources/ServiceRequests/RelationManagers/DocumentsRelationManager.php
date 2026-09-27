<?php

namespace App\Filament\Resources\ServiceRequests\RelationManagers;

use App\Enums\DocumentVerificationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen Persyaratan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_requirement_id')
                    ->label('Jenis Dokumen Persyaratan')
                    ->relationship('serviceRequirement', 'name')
                    ->required(),
                FileUpload::make('file_path')
                    ->label('File Dokumen')
                    ->disk('public')
                    ->directory('service_documents')
                    ->required(),
                TextInput::make('original_name')
                    ->label('Nama File Asli')
                    ->maxLength(255),
                Select::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options(DocumentVerificationStatus::class)
                    ->default(DocumentVerificationStatus::Pending)
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan Pemeriksa')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->columns([
                TextColumn::make('serviceRequirement.name')
                    ->label('Jenis Persyaratan')
                    ->sortable(),
                TextColumn::make('original_name')
                    ->label('Nama Berkas')
                    ->searchable(),
                TextColumn::make('verification_status')
                    ->label('Verifikasi')
                    ->badge()
                    ->color(fn (DocumentVerificationStatus $state): string => match ($state) {
                        DocumentVerificationStatus::Valid => 'success',
                        DocumentVerificationStatus::Invalid => 'danger',
                        DocumentVerificationStatus::NeedRevision => 'warning',
                        DocumentVerificationStatus::Pending => 'gray',
                    }),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40),
            ])
            ->headerActions([
                CreateAction::make()->label('Unggah Dokumen'),
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
