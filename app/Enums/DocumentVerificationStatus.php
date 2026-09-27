<?php

namespace App\Enums;

enum DocumentVerificationStatus: string
{
    case Pending = 'pending';
    case Valid = 'valid';
    case RevisionNeeded = 'revision_needed';

    public const PENDING = self::Pending;
    public const VALID = self::Valid;
    public const REVISION_NEEDED = self::RevisionNeeded;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Verifikasi',
            self::Valid => 'Sesuai / Valid',
            self::RevisionNeeded => 'Perlu Perbaikan',
        };
    }
}
