<?php

namespace App\Enums;

enum InformationCategory: string
{
    case PROGRAM = 'program';
    case REHABILITATION = 'rehabilitation';
    case DISABILITY = 'disability';
    case ELDERLY = 'elderly';
    case COMPLAINT = 'complaint';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PROGRAM => 'Program Sosial',
            self::REHABILITATION => 'Rehabilitasi Sosial',
            self::DISABILITY => 'Disabilitas',
            self::ELDERLY => 'Lanjut Usia',
            self::COMPLAINT => 'Pengaduan',
            self::OTHER => 'Lainnya',
        };
    }
}
