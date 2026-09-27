<?php

namespace App\Filament\Resources\ClientCategories;

use App\Filament\Resources\ClientCategories\Pages\CreateClientCategory;
use App\Filament\Resources\ClientCategories\Pages\EditClientCategory;
use App\Filament\Resources\ClientCategories\Pages\ListClientCategories;
use App\Filament\Resources\ClientCategories\Schemas\ClientCategoryForm;
use App\Filament\Resources\ClientCategories\Tables\ClientCategoriesTable;
use App\Models\ClientCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClientCategoryResource extends Resource
{
    protected static ?string $model = ClientCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori Klien Rehab';

    protected static ?string $modelLabel = 'Kategori Klien Rehab';

    protected static ?string $pluralModelLabel = 'Kategori Klien Rehab';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ClientCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClientCategories::route('/'),
            'create' => CreateClientCategory::route('/create'),
            'edit' => EditClientCategory::route('/{record}/edit'),
        ];
    }
}
