<?php

namespace App\Enums;

enum MinistryDecision: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Keputusan',
            self::APPROVED => 'Disetujui Kemensos',
            self::REJECTED => 'Ditolak Kemensos',
        };
    }
}
