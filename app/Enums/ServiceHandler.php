<?php

namespace App\Enums;

enum ServiceHandler: string
{
    case Generic = 'generic';
    case Dtsen = 'dtsen';
    case Pbi = 'pbi';

    public const GENERIC = self::Generic;
    public const DTSEN = self::Dtsen;
    public const PBI = self::Pbi;

    public function label(): string
    {
        return match ($this) {
            self::Generic => 'Layanan Umum',
            self::Dtsen => 'Surat Keterangan DTSEN',
            self::Pbi => 'Reaktivasi KIS / PBI-JK',
        };
    }
}
