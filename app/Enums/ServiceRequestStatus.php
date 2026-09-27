<?php

namespace App\Enums;

enum ServiceRequestStatus: string
{
    case Submitted = 'submitted';
    case DocumentCheck = 'document_check';
    case RevisionRequested = 'revision_requested';
    case DataVerification = 'data_verification';
    case EligibilityVerification = 'eligibility_verification';
    case Verification = 'verification';
    case Assessment = 'assessment';
    case AwaitingApproval = 'awaiting_approval';
    case Issued = 'issued';
    case RecommendationIssued = 'recommendation_issued';
    case ProposedToMinistry = 'proposed_to_ministry';
    case MinistryApproved = 'ministry_approved';
    case MinistryRejected = 'ministry_rejected';
    case Reactivated = 'reactivated';
    case InProcess = 'in_process';
    case Completed = 'completed';
    case Rejected = 'rejected';

    public const SUBMITTED = self::Submitted;
    public const DOCUMENT_CHECK = self::DocumentCheck;
    public const REVISION_REQUESTED = self::RevisionRequested;
    public const DATA_VERIFICATION = self::DataVerification;
    public const ELIGIBILITY_VERIFICATION = self::EligibilityVerification;
    public const VERIFICATION = self::Verification;
    public const ASSESSMENT = self::Assessment;
    public const AWAITING_APPROVAL = self::AwaitingApproval;
    public const ISSUED = self::Issued;
    public const RECOMMENDATION_ISSUED = self::RecommendationIssued;
    public const PROPOSED_TO_MINISTRY = self::ProposedToMinistry;
    public const MINISTRY_APPROVED = self::MinistryApproved;
    public const MINISTRY_REJECTED = self::MinistryRejected;
    public const REACTIVATED = self::Reactivated;
    public const IN_PROCESS = self::InProcess;
    public const COMPLETED = self::Completed;
    public const REJECTED = self::Rejected;

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Diajukan',
            self::DocumentCheck => 'Pemeriksaan Berkas',
            self::RevisionRequested => 'Permintaan Perbaikan Berkas',
            self::DataVerification => 'Verifikasi Data (SIKS-NG)',
            self::EligibilityVerification => 'Verifikasi Kelayakan',
            self::Verification => 'Verifikasi',
            self::Assessment => 'Assessment',
            self::AwaitingApproval => 'Menunggu Persetujuan',
            self::Issued => 'Surat Keterangan Terbit',
            self::RecommendationIssued => 'Rekomendasi Terbit',
            self::ProposedToMinistry => 'Diusulkan ke Kemensos',
            self::MinistryApproved => 'Disetujui Kemensos',
            self::MinistryRejected => 'Ditolak Kemensos',
            self::Reactivated => 'Kepesertaan Aktif Kembali',
            self::InProcess => 'Sedang Diproses',
            self::Completed => 'Selesai',
            self::Rejected => 'Ditolak',
        };
    }
}
