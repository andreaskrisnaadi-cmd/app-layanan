<?php

namespace App\Enums;

enum RehabilitationHandlingType: string
{
    case Direct = 'direct';
    case Referral = 'referral';
    case Both = 'both';

    public const DIRECT = self::Direct;
    public const REFERRAL = self::Referral;
    public const BOTH = self::Both;
    public const DirectService = self::Direct;
    public const DIRECT_SERVICE = self::Direct;

    public function label(): string
    {
        return match ($this) {
            self::Direct => 'Pelayanan Langsung',
            self::Referral => 'Rujukan ke Lembaga',
            self::Both => 'Pelayanan Langsung & Rujukan',
        };
    }
}
