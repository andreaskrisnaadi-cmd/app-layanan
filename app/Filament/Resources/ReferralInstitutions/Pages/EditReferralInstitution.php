<?php

namespace App\Filament\Resources\ReferralInstitutions\Pages;

use App\Filament\Resources\ReferralInstitutions\ReferralInstitutionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReferralInstitution extends EditRecord
{
    protected static string $resource = ReferralInstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
