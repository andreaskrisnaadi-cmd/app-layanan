<?php

namespace App\Enums;

enum ComplaintAttachmentType: string
{
    case Photo = 'photo';
    case Document = 'document';

    public const PHOTO = self::Photo;
    public const DOCUMENT = self::Document;

    public function label(): string
    {
        return match ($this) {
            self::Photo => 'Foto',
            self::Document => 'Dokumen',
        };
    }
}
