<?php

namespace App\Enums;

enum PbiReason: string
{
    case CHRONIC = 'chronic';
    case CATASTROPHIC = 'catastrophic';
    case EMERGENCY = 'emergency';
    case NEWBORN = 'newborn';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CHRONIC => 'Penyakit Kronis',
            self::CATASTROPHIC => 'Penyakit Katastropik',
            self::EMERGENCY => 'Kondisi Darurat Medis',
            self::NEWBORN => 'Bayi Baru Lahir dari Ibu PBI',
            self::OTHER => 'Lainnya',
        };
    }
}
