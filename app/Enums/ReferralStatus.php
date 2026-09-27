<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Accepted = 'accepted';
    case InService = 'in_service';
    case Completed = 'completed';
    case Declined = 'declined';
    case Cancelled = 'cancelled';

    public const DRAFT = self::Draft;
    public const SENT = self::Sent;
    public const ACCEPTED = self::Accepted;
    public const IN_SERVICE = self::InService;
    public const COMPLETED = self::Completed;
    public const DECLINED = self::Declined;
    public const CANCELLED = self::Cancelled;

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf Rujukan',
            self::Sent => 'Terkirim ke Lembaga',
            self::Accepted => 'Diterima oleh Lembaga',
            self::InService => 'Sedang Dilayani di Lembaga',
            self::Completed => 'Pelayanan Selesai',
            self::Declined => 'Ditolak Lembaga',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
