<?php

namespace App\Enums;

enum ServiceRequestStatus: string
{
    case SUBMITTED = 'submitted';
    case DOCUMENT_CHECK = 'document_check';
    case REVISION_REQUESTED = 'revision_requested';
    case DATA_VERIFICATION = 'data_verification';
    case ELIGIBILITY_VERIFICATION = 'eligibility_verification';
    case VERIFICATION = 'verification';
    case ASSESSMENT = 'assessment';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case ISSUED = 'issued';
    case RECOMMENDATION_ISSUED = 'recommendation_issued';
    case PROPOSED_TO_MINISTRY = 'proposed_to_ministry';
    case MINISTRY_APPROVED = 'ministry_approved';
    case MINISTRY_REJECTED = 'ministry_rejected';
    case REACTIVATED = 'reactivated';
    case IN_PROCESS = 'in_process';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Diajukan',
            self::DOCUMENT_CHECK => 'Pemeriksaan Berkas',
            self::REVISION_REQUESTED => 'Permintaan Perbaikan Berkas',
            self::DATA_VERIFICATION => 'Verifikasi Data (SIKS-NG)',
            self::ELIGIBILITY_VERIFICATION => 'Verifikasi Kelayakan',
            self::VERIFICATION => 'Verifikasi',
            self::ASSESSMENT => 'Assessment',
            self::AWAITING_APPROVAL => 'Menunggu Persetujuan',
            self::ISSUED => 'Surat Keterangan Terbit',
            self::RECOMMENDATION_ISSUED => 'Rekomendasi Terbit',
            self::PROPOSED_TO_MINISTRY => 'Diusulkan ke Kemensos',
            self::MINISTRY_APPROVED => 'Disetujui Kemensos',
            self::MINISTRY_REJECTED => 'Ditolak Kemensos',
            self::REACTIVATED => 'Kepesertaan Aktif Kembali',
            self::IN_PROCESS => 'Sedang Diproses',
            self::COMPLETED => 'Selesai',
            self::REJECTED => 'Ditolak',
        };
    }
}
