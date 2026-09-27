<?php

namespace App\Filament\Resources\RehabilitationCases\Pages;

use App\Filament\Resources\RehabilitationCases\RehabilitationCaseResource;
use App\Models\NumberSequence;
use Filament\Resources\Pages\CreateRecord;

class CreateRehabilitationCase extends CreateRecord
{
    protected static string $resource = RehabilitationCaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['case_number'])) {
            $data['case_number'] = NumberSequence::getNextNumber('REHAB', now()->format('Ym'), 5);
        }
        if (empty($data['received_at'])) {
            $data['received_at'] = now();
        }
        if (empty($data['officer_id']) && auth()->check()) {
            $data['officer_id'] = auth()->id();
        }

        return $data;
    }
}
