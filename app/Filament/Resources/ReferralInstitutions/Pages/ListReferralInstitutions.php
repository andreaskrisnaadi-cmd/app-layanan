<?php

namespace App\Filament\Resources\ReferralInstitutions\Pages;

use App\Filament\Resources\ReferralInstitutions\ReferralInstitutionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReferralInstitutions extends ListRecords
{
    protected static string $resource = ReferralInstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
