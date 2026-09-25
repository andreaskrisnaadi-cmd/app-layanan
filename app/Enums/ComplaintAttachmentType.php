<?php

namespace App\Enums;

enum ComplaintAttachmentType: string
{
    case PHOTO = 'photo';
    case DOCUMENT = 'document';

    public function label(): string
    {
        return match ($this) {
            self::PHOTO => 'Foto',
            self::DOCUMENT => 'Dokumen',
        };
    }
}
