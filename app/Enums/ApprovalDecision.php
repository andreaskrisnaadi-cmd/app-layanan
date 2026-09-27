<?php

namespace App\Enums;

enum ApprovalDecision: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Returned = 'returned';

    public const PENDING = self::Pending;
    public const APPROVED = self::Approved;
    public const RETURNED = self::Returned;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Keputusan',
            self::Approved => 'Disetujui / Diparaf',
            self::Returned => 'Dikembalikan / Ditolak',
        };
    }
}
