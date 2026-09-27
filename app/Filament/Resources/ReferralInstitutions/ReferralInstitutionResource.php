<?php

namespace App\Filament\Resources\ReferralInstitutions;

use App\Filament\Resources\ReferralInstitutions\Pages\CreateReferralInstitution;
use App\Filament\Resources\ReferralInstitutions\Pages\EditReferralInstitution;
use App\Filament\Resources\ReferralInstitutions\Pages\ListReferralInstitutions;
use App\Filament\Resources\ReferralInstitutions\Schemas\ReferralInstitutionForm;
use App\Filament\Resources\ReferralInstitutions\Tables\ReferralInstitutionsTable;
use App\Models\ReferralInstitution;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReferralInstitutionResource extends Resource
{
    protected static ?string $model = ReferralInstitution::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Lembaga Rujukan';

    protected static ?string $modelLabel = 'Lembaga Rujukan';

    protected static ?string $pluralModelLabel = 'Lembaga Rujukan';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ReferralInstitutionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferralInstitutionsTable::configure($table);
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
            'index' => ListReferralInstitutions::route('/'),
            'create' => CreateReferralInstitution::route('/create'),
            'edit' => EditReferralInstitution::route('/{record}/edit'),
        ];
    }
}
