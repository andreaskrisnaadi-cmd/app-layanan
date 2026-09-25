<?php

namespace App\Enums;

enum RehabilitationHandlingType: string
{
    case DIRECT = 'direct';
    case REFERRAL = 'referral';
    case BOTH = 'both';

    public function label(): string
    {
        return match ($this) {
            self::DIRECT => 'Pelayanan Langsung',
            self::REFERRAL => 'Rujukan ke Lembaga',
            self::BOTH => 'Pelayanan Langsung & Rujukan',
        };
    }
}
