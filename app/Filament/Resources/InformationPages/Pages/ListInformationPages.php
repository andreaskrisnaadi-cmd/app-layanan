<?php

namespace App\Filament\Resources\InformationPages\Pages;

use App\Filament\Resources\InformationPages\InformationPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInformationPages extends ListRecords
{
    protected static string $resource = InformationPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Halaman Informasi'),
        ];
    }
}
