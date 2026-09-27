<?php

namespace App\Filament\Resources\DtsenPurposes\Pages;

use App\Filament\Resources\DtsenPurposes\DtsenPurposeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDtsenPurposes extends ListRecords
{
    protected static string $resource = DtsenPurposeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
