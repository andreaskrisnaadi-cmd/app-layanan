<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case Received = 'received';
    case Verification = 'verification';
    case ClarificationRequested = 'clarification_requested';
    case Dispatched = 'dispatched';
    case InHandling = 'in_handling';
    case Resolved = 'resolved';
    case Duplicate = 'duplicate';
    case Invalid = 'invalid';

    public const RECEIVED = self::Received;
    public const VERIFICATION = self::Verification;
    public const CLARIFICATION_REQUESTED = self::ClarificationRequested;
    public const DISPATCHED = self::Dispatched;
    public const IN_HANDLING = self::InHandling;
    public const RESOLVED = self::Resolved;
    public const DUPLICATE = self::Duplicate;
    public const INVALID = self::Invalid;

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Laporan Diterima',
            self::Verification => 'Verifikasi Awal',
            self::ClarificationRequested => 'Permintaan Klarifikasi',
            self::Dispatched => 'Didisposisikan',
            self::InHandling => 'Dalam Penanganan',
            self::Resolved => 'Selesai Ditangani',
            self::Duplicate => 'Duplikat Laporan',
            self::Invalid => 'Tidak Valid / Ditolak',
        };
    }
}
