<?php

namespace App\Filament\Resources\ServiceRequests;

use App\Filament\Resources\ServiceRequests\Pages\CreateServiceRequest;
use App\Filament\Resources\ServiceRequests\Pages\EditServiceRequest;
use App\Filament\Resources\ServiceRequests\Pages\ListServiceRequests;
use App\Filament\Resources\ServiceRequests\Schemas\ServiceRequestForm;
use App\Filament\Resources\ServiceRequests\Tables\ServiceRequestsTable;
use App\Models\ServiceRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Layanan Utama';

    protected static ?string $navigationLabel = 'Pengajuan Layanan';

    protected static ?string $modelLabel = 'Pengajuan Layanan';

    protected static ?string $pluralModelLabel = 'Pengajuan Layanan';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'request_number';

    public static function form(Schema $schema): Schema
    {
        return ServiceRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ServiceRequests\RelationManagers\DocumentsRelationManager::class,
            \App\Filament\Resources\ServiceRequests\RelationManagers\DtsenCertificateRelationManager::class,
            \App\Filament\Resources\ServiceRequests\RelationManagers\PbiReactivationRelationManager::class,
            \App\Filament\Resources\ServiceRequests\RelationManagers\DispositionsRelationManager::class,
            \App\Filament\Resources\ServiceRequests\RelationManagers\StatusHistoriesRelationManager::class,
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

        return $query->orderByDesc('is_priority')->orderByDesc('submitted_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceRequests::route('/'),
            'create' => CreateServiceRequest::route('/create'),
            'edit' => EditServiceRequest::route('/{record}/edit'),
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
