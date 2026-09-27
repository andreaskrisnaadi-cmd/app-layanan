<?php

namespace App\Filament\Resources\DtsenPurposes\Pages;

use App\Filament\Resources\DtsenPurposes\DtsenPurposeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDtsenPurpose extends EditRecord
{
    protected static string $resource = DtsenPurposeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
