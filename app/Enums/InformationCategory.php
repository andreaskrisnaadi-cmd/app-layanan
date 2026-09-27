<?php

namespace App\Enums;

enum InformationCategory: string
{
    case Program = 'program';
    case Rehabilitation = 'rehabilitation';
    case Disability = 'disability';
    case Elderly = 'elderly';
    case Complaint = 'complaint';
    case Other = 'other';

    public const PROGRAM = self::Program;
    public const REHABILITATION = self::Rehabilitation;
    public const DISABILITY = self::Disability;
    public const ELDERLY = self::Elderly;
    public const COMPLAINT = self::Complaint;
    public const OTHER = self::Other;

    public function label(): string
    {
        return match ($this) {
            self::Program => 'Program Sosial',
            self::Rehabilitation => 'Rehabilitasi Sosial',
            self::Disability => 'Disabilitas',
            self::Elderly => 'Lanjut Usia',
            self::Complaint => 'Pengaduan',
            self::Other => 'Lainnya',
        };
    }
}
