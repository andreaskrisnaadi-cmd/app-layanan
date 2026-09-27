<?php

namespace App\Filament\Resources\ServiceRequests\Pages;

use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\NumberSequence;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceRequest extends CreateRecord
{
    protected static string $resource = ServiceRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['request_number'])) {
            $data['request_number'] = NumberSequence::getNextNumber('REQ', now()->format('Ym'), 5);
        }
        if (empty($data['submitted_at'])) {
            $data['submitted_at'] = now();
        }
        if (empty($data['submitter_id']) && auth()->check()) {
            $data['submitter_id'] = auth()->id();
        }

        return $data;
    }
}
