<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case IN_SERVICE = 'in_service';
    case COMPLETED = 'completed';
    case DECLINED = 'declined';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draf Rujukan',
            self::SENT => 'Terkirim ke Lembaga',
            self::ACCEPTED => 'Diterima oleh Lembaga',
            self::IN_SERVICE => 'Sedang Dilayani di Lembaga',
            self::COMPLETED => 'Pelayanan Selesai',
            self::DECLINED => 'Ditolak Lembaga',
            self::CANCELLED => 'Dibatalkan',
        };
    }
}
