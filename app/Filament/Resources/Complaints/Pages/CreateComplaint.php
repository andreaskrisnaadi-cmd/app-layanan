<?php

namespace App\Filament\Resources\Complaints\Pages;

use App\Filament\Resources\Complaints\ComplaintResource;
use App\Models\NumberSequence;
use Filament\Resources\Pages\CreateRecord;

class CreateComplaint extends CreateRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['complaint_number'])) {
            $data['complaint_number'] = NumberSequence::getNextNumber('ADU', now()->format('Ym'), 5);
        }
        if (empty($data['reported_at'])) {
            $data['reported_at'] = now();
        }
        if (empty($data['reporter_id']) && auth()->check()) {
            $data['reporter_id'] = auth()->id();
        }

        return $data;
    }
}
