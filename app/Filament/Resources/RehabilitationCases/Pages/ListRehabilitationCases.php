<?php

namespace App\Filament\Resources\RehabilitationCases\Pages;

use App\Filament\Resources\RehabilitationCases\RehabilitationCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRehabilitationCases extends ListRecords
{
    protected static string $resource = RehabilitationCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
