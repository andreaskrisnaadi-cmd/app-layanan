<?php

namespace App\Filament\Resources\Complaints;

use App\Filament\Resources\Complaints\Pages\CreateComplaint;
use App\Filament\Resources\Complaints\Pages\EditComplaint;
use App\Filament\Resources\Complaints\Pages\ListComplaints;
use App\Filament\Resources\Complaints\Schemas\ComplaintForm;
use App\Filament\Resources\Complaints\Tables\ComplaintsTable;
use App\Models\Complaint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaduan';

    protected static ?string $navigationLabel = 'Laporan Pengaduan';

    protected static ?string $modelLabel = 'Laporan Pengaduan';

    protected static ?string $pluralModelLabel = 'Laporan Pengaduan';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'complaint_number';

    public static function form(Schema $schema): Schema
    {
        return ComplaintForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Complaints\RelationManagers\AttachmentsRelationManager::class,
            \App\Filament\Resources\Complaints\RelationManagers\DispositionsRelationManager::class,
            \App\Filament\Resources\Complaints\RelationManagers\StatusHistoriesRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->hasAnyRole(['Operator Kecamatan/Desa', 'operator_kecamatan', 'operator_desa'])) {
                if ($user->village_id) {
                    $query->where('village_id', $user->village_id);
                } elseif ($user->district_id) {
                    $query->whereHas('village', fn (Builder $q) => $q->where('district_id', $user->district_id));
                }
            }
        }

        return $query->latest('reported_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComplaints::route('/'),
            'create' => CreateComplaint::route('/create'),
            'edit' => EditComplaint::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
