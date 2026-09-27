<?php

namespace App\Enums;

enum RehabilitationCaseStatus: string
{
    case Received = 'received';
    case Assessment = 'assessment';
    case ServicePlanning = 'service_planning';
    case InService = 'in_service';
    case Monitoring = 'monitoring';
    case Closed = 'closed';

    public const RECEIVED = self::Received;
    public const ASSESSMENT = self::Assessment;
    public const SERVICE_PLANNING = self::ServicePlanning;
    public const IN_SERVICE = self::InService;
    public const MONITORING = self::Monitoring;
    public const CLOSED = self::Closed;

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Kasus Diterima',
            self::Assessment => 'Assessment',
            self::ServicePlanning => 'Rencana Pelayanan',
            self::InService => 'Dalam Pelayanan',
            self::Monitoring => 'Monitoring Perkembangan',
            self::Closed => 'Kasus Ditutup / Selesai',
        };
    }
}
