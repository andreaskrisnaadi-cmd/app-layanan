<?php

namespace App\Enums;

enum MinistryDecision: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public const PENDING = self::Pending;
    public const APPROVED = self::Approved;
    public const REJECTED = self::Rejected;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Keputusan',
            self::Approved => 'Disetujui Kemensos',
            self::Rejected => 'Ditolak Kemensos',
        };
    }
}
