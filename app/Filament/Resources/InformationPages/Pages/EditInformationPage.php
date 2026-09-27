<?php

namespace App\Filament\Resources\InformationPages\Pages;

use App\Filament\Resources\InformationPages\InformationPageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditInformationPage extends EditRecord
{
    protected static string $resource = InformationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
