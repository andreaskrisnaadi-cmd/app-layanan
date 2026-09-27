<?php

namespace App\Enums;

enum PublishStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public const DRAFT = self::Draft;
    public const PUBLISHED = self::Published;
    public const ARCHIVED = self::Archived;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Published => 'Diterbitkan',
            self::Archived => 'Diarsipkan',
        };
    }
}
