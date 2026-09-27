<?php

namespace App\Filament\Resources\RehabilitationCases\Pages;

use App\Filament\Resources\RehabilitationCases\RehabilitationCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRehabilitationCase extends EditRecord
{
    protected static string $resource = RehabilitationCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
