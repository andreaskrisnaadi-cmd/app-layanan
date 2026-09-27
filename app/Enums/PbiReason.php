<?php

namespace App\Enums;

enum PbiReason: string
{
    case Chronic = 'chronic';
    case Catastrophic = 'catastrophic';
    case Emergency = 'emergency';
    case Newborn = 'newborn';
    case Other = 'other';

    public const CHRONIC = self::Chronic;
    public const CATASTROPHIC = self::Catastrophic;
    public const EMERGENCY = self::Emergency;
    public const NEWBORN = self::Newborn;
    public const OTHER = self::Other;

    public function label(): string
    {
        return match ($this) {
            self::Chronic => 'Penyakit Kronis',
            self::Catastrophic => 'Penyakit Katastropik',
            self::Emergency => 'Kondisi Darurat Medis',
            self::Newborn => 'Bayi Baru Lahir dari Ibu PBI',
            self::Other => 'Lainnya',
        };
    }
}
