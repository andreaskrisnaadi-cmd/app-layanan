<?php

namespace App\Filament\Resources\ComplaintCategories;

use App\Filament\Resources\ComplaintCategories\Pages\CreateComplaintCategory;
use App\Filament\Resources\ComplaintCategories\Pages\EditComplaintCategory;
use App\Filament\Resources\ComplaintCategories\Pages\ListComplaintCategories;
use App\Filament\Resources\ComplaintCategories\Schemas\ComplaintCategoryForm;
use App\Filament\Resources\ComplaintCategories\Tables\ComplaintCategoriesTable;
use App\Models\ComplaintCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComplaintCategoryResource extends Resource
{
    protected static ?string $model = ComplaintCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori Pengaduan';

    protected static ?string $modelLabel = 'Kategori Pengaduan';

    protected static ?string $pluralModelLabel = 'Kategori Pengaduan';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ComplaintCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintCategoriesTable::configure($table);
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
            'index' => ListComplaintCategories::route('/'),
            'create' => CreateComplaintCategory::route('/create'),
            'edit' => EditComplaintCategory::route('/{record}/edit'),
        ];
    }
}
