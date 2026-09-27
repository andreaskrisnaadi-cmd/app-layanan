<?php

namespace Database\Seeders;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintAttachmentType;
use App\Enums\ComplaintStatus;
use App\Enums\DocumentVerificationStatus;
use App\Enums\MinistryDecision;
use App\Enums\PbiReason;
use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Enums\RehabilitationHandlingType;
use App\Enums\ServiceRequestStatus;
use App\Models\Approval;
use App\Models\Assessment;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\Disposition;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\MonitoringRecord;
use App\Models\NumberSequence;
use App\Models\PbiReactivation;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first();
        $petugasLinjamsos = User::where('email', 'petugas.pelayanan@dinsos.blitarkab.go.id')->first();
        $petugasRehsos = User::where('email', 'petugas.rehsos@dinsos.blitarkab.go.id')->first();
        $kabid = User::where('email', 'kabid.linjamsos@dinsos.blitarkab.go.id')->first();
        $kadis = User::where('email', 'kadis@dinsos.blitarkab.go.id')->first();
        $wargaBudi = User::where('email', 'warga.budi@example.com')->first();
        $wargaSiti = User::where('email', 'warga.siti@example.com')->first();

        $dtsenType = ServiceType::where('code', 'DTSEN')->first();
        $pbiType = ServiceType::where('code', 'PBI')->first();
        $linjamsosUnit = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsosUnit = WorkUnit::where('name', 'like', '%Rehsos%')->first();

        $satreyan = Village::where('name', 'like', '%Satreyan%')->first();
        $tlogo = Village::where('name', 'like', '%Tlogo%')->first();
        $spmbPurpose = DtsenPurpose::where('code', 'spmb')->first();
        $kipPurpose = DtsenPurpose::where('code', 'kip_kuliah')->first();

        $currentPeriod = now()->format('Ym');

        // =========================================================================
        // 1. TRANSAKSI LAYANAN 1: SK DTSEN (Status: Selesai / Terbit)
        // =========================================================================
        $ticketDtsen1 = NumberSequence::getNextNumber('DTSEN', $currentPeriod);
        $requestDtsen1 = ServiceRequest::create([
            'request_number' => $ticketDtsen1,
            'service_type_id' => $dtsenType->id,
            'submitter_id' => $wargaBudi->id,
            'applicant_name' => 'Budi Santoso',
            'applicant_nik' => '3505011205850001',
            'family_card_number' => '3505010101150002',
            'address' => 'RT 02 RW 03, Kelurahan Satreyan, Kecamatan Kanigoro',
            'village_id' => $satreyan->id,
            'phone' => '085712345678',
            'submitted_at' => now()->subDays(2),
            'officer_id' => $petugasLinjamsos->id,
            'work_unit_id' => $linjamsosUnit->id,
            'status' => ServiceRequestStatus::COMPLETED,
            'is_priority' => false,
            'verification_result' => 'Data pemohon dan orang yang diterangkan valid terdaftar di SIKS-NG Desil 2.',
            'officer_notes' => 'Dokumen lengkap dan sesuai ketentuan SPMB Jalur Afirmasi.',
            'service_result' => 'Surat Keterangan DTSEN telah diterbitkan dan diunduh oleh pemohon.',
            'completed_at' => now()->subHours(4),
        ]);

        // Dokumen Persyaratan Layanan 1
        foreach ($dtsenType->requirements as $req) {
            ServiceRequestDocument::create([
                'service_request_id' => $requestDtsen1->id,
                'service_requirement_id' => $req->id,
                'file_path' => 'documents/dtsen/' . Str::slug($req->name) . '-sample.pdf',
                'original_name' => $req->name . ' Budi Santoso.pdf',
                'verification_status' => DocumentVerificationStatus::VALID,
                'notes' => 'Berkas valid dan terbaca jelas.',
            ]);
        }

        // Detail SK DTSEN
        $certNumber = '400.9/101/409.105/' . now()->year;
        $vfyCode = 'DTSEN-' . now()->format('Ym') . '-' . strtoupper(Str::random(6));
        $certDtsen1 = DtsenCertificate::create([
            'service_request_id' => $requestDtsen1->id,
            'dtsen_purpose_id' => $spmbPurpose->id,
            'purpose_description' => 'Persyaratan pendaftaran SPMB Jalur Afirmasi tingkat SMA Negeri di Blitar',
            'subject_name' => 'Ahmad Fauzi Santoso',
            'subject_nik' => '3505012001080001',
            'relationship_to_applicant' => 'Anak Kandung',
            'is_registered' => true,
            'decile' => 2,
            'checked_at' => now()->subDays(1)->subHours(5),
            'checker_id' => $petugasLinjamsos->id,
            'certificate_number' => $certNumber,
            'issued_at' => now()->subHours(6),
            'valid_until' => now()->addDays(90),
            'signer_id' => $kadis->id,
            'file_path' => 'certificates/sk-dtsen-' . Str::slug($ticketDtsen1) . '.pdf',
            'verification_code' => $vfyCode,
        ]);

        // Persetujuan Berjenjang (Step 1: Kabid Paraf, Step 2: Kadis Tanda Tangan)
        Approval::create([
            'approvable_type' => DtsenCertificate::class,
            'approvable_id' => $certDtsen1->id,
            'step' => 1,
            'approver_id' => $kabid->id,
            'decision' => ApprovalDecision::APPROVED,
            'notes' => 'Telah diperiksa, data desil 2 memenuhi kriteria SPMB Afirmasi (desil <= 5). Disetujui paraf.',
            'decided_at' => now()->subHours(8),
        ]);

        Approval::create([
            'approvable_type' => DtsenCertificate::class,
            'approvable_id' => $certDtsen1->id,
            'step' => 2,
            'approver_id' => $kadis->id,
            'decision' => ApprovalDecision::APPROVED,
            'notes' => 'Disetujui dan ditandatangani secara digital.',
            'decided_at' => now()->subHours(6),
        ]);

        // Status Histories
        $statusesDtsen = [
            [null, ServiceRequestStatus::SUBMITTED->value, 'Pengajuan baru diterima oleh sistem.', now()->subDays(2), $wargaBudi->id],
            [ServiceRequestStatus::SUBMITTED->value, ServiceRequestStatus::DOCUMENT_CHECK->value, 'Pemeriksaan berkas persyaratan oleh petugas.', now()->subDays(1)->subHours(8), $petugasLinjamsos->id],
            [ServiceRequestStatus::DOCUMENT_CHECK->value, ServiceRequestStatus::DATA_VERIFICATION->value, 'Pengecekan data di SIKS-NG: terdaftar Desil 2.', now()->subDays(1)->subHours(5), $petugasLinjamsos->id],
            [ServiceRequestStatus::DATA_VERIFICATION->value, ServiceRequestStatus::AWAITING_APPROVAL->value, 'Draf surat diteruskan ke Pejabat Penandatangan.', now()->subHours(10), $petugasLinjamsos->id],
            [ServiceRequestStatus::AWAITING_APPROVAL->value, ServiceRequestStatus::ISSUED->value, 'Surat Keterangan ditandatangani Kadis dan terbit.', now()->subHours(6), $kadis->id],
            [ServiceRequestStatus::ISSUED->value, ServiceRequestStatus::COMPLETED->value, 'Layanan selesai.', now()->subHours(4), $petugasLinjamsos->id],
        ];

        foreach ($statusesDtsen as $item) {
            StatusHistory::create([
                'statusable_type' => ServiceRequest::class,
                'statusable_id' => $requestDtsen1->id,
                'from_status' => $item[0],
                'to_status' => $item[1],
                'notes' => $item[2],
                'created_at' => $item[3],
                'user_id' => $item[4],
            ]);
        }

        // =========================================================================
        // 2. TRANSAKSI LAYANAN 1: SK DTSEN (Status: Menunggu Persetujuan)
        // =========================================================================
        $ticketDtsen2 = NumberSequence::getNextNumber('DTSEN', $currentPeriod);
        $requestDtsen2 = ServiceRequest::create([
            'request_number' => $ticketDtsen2,
            'service_type_id' => $dtsenType->id,
            'submitter_id' => $wargaSiti->id,
            'applicant_name' => 'Siti Aminah',
            'applicant_nik' => '3505015508900002',
            'family_card_number' => '3505011002200003',
            'address' => 'Dusun Tlogo RT 01 RW 01, Desa Tlogo, Kanigoro',
            'village_id' => $tlogo->id,
            'phone' => '085787654321',
            'submitted_at' => now()->subDay(),
            'officer_id' => $petugasLinjamsos->id,
            'work_unit_id' => $linjamsosUnit->id,
            'status' => ServiceRequestStatus::AWAITING_APPROVAL,
            'is_priority' => false,
            'verification_result' => 'Terdaftar di SIKS-NG desil 3.',
            'officer_notes' => 'Menunggu paraf Kabid Linjamsos.',
        ]);

        $certDtsen2 = DtsenCertificate::create([
            'service_request_id' => $requestDtsen2->id,
            'dtsen_purpose_id' => $kipPurpose->id,
            'purpose_description' => 'Persyaratan Beasiswa KIP Kuliah 2026',
            'subject_name' => 'Rina Salsabila',
            'subject_nik' => '3505014502050004',
            'relationship_to_applicant' => 'Anak Kandung',
            'is_registered' => true,
            'decile' => 3,
            'checked_at' => now()->subHours(5),
            'checker_id' => $petugasLinjamsos->id,
            'verification_code' => 'DTSEN-' . now()->format('Ym') . '-' . strtoupper(Str::random(6)),
        ]);

        Approval::create([
            'approvable_type' => DtsenCertificate::class,
            'approvable_id' => $certDtsen2->id,
            'step' => 1,
            'approver_id' => $kabid->id,
            'decision' => ApprovalDecision::PENDING,
            'notes' => 'Menunggu telaah dan paraf Kepala Bidang.',
        ]);

        // =========================================================================
        // 3. TRANSAKSI LAYANAN 2: REAKTIVASI KIS/PBI-JK (Kondisi Darurat Medis)
        // =========================================================================
        $ticketPbi1 = NumberSequence::getNextNumber('PBI', $currentPeriod);
        $requestPbi1 = ServiceRequest::create([
            'request_number' => $ticketPbi1,
            'service_type_id' => $pbiType->id,
            'submitter_id' => $wargaBudi->id,
            'applicant_name' => 'Budi Santoso',
            'applicant_nik' => '3505011205850001',
            'family_card_number' => '3505010101150002',
            'address' => 'Kelurahan Satreyan, Kanigoro',
            'village_id' => $satreyan->id,
            'phone' => '085712345678',
            'submitted_at' => now()->subDays(3),
            'officer_id' => $petugasLinjamsos->id,
            'work_unit_id' => $linjamsosUnit->id,
            'status' => ServiceRequestStatus::PROPOSED_TO_MINISTRY,
            'is_priority' => true, // Prioritas darurat medis
            'verification_result' => 'Pasien rawat inap darurat di RSUD Ngudi Waluyo Wlingi. Desil 1, kartu nonaktif 2 bulan.',
            'officer_notes' => 'Surat rekomendasi terbit dan usulan telah diinput ke SIKS-NG Kemensos.',
        ]);

        PbiReactivation::create([
            'service_request_id' => $requestPbi1->id,
            'participant_name' => 'Slamet Santoso (Orang Tua Pemohon)',
            'participant_nik' => '3505010107520005',
            'bpjs_card_number' => '0001234567890',
            'deactivated_date' => now()->subMonths(2)->toDateString(),
            'reason' => PbiReason::EMERGENCY,
            'health_facility_name' => 'RSUD Ngudi Waluyo Wlingi',
            'health_letter_number' => '445/782/RSUD-NW/2026',
            'decile' => 1,
            'eligibility_notes' => 'Pasien stroke akut butuh penanganan intensif segera. Memenuhi kriteria darurat medis.',
            'recommendation_number' => '440/045/409.105/' . now()->year,
            'recommendation_issued_at' => now()->subDays(2),
            'signer_id' => $kadis->id,
            'proposed_to_ministry_at' => now()->subDay(),
            'ministry_decision' => MinistryDecision::PENDING,
        ]);

        // =========================================================================
        // 4. TRANSAKSI LAYANAN 5: PENGADUAN SOSIAL
        // =========================================================================
        $catComplaintLansia = ComplaintCategory::where('name', 'like', '%Lanjut Usia%')->first()
            ?? ComplaintCategory::first();

        $complaintNumber = NumberSequence::getNextNumber('ADU', $currentPeriod);
        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'complaint_category_id' => $catComplaintLansia->id,
            'reporter_id' => $wargaSiti->id,
            'reporter_name' => 'Siti Aminah',
            'reporter_phone' => '085787654321',
            'location_detail' => 'Dekat gardu ronda Dusun Tlogo RT 02 RW 01, Desa Tlogo, Kanigoro',
            'village_id' => $tlogo->id,
            'description' => 'Ditemukan seorang nenek lanjut usia (Mbah Sarinem) sebatang kara dan sedang sakit lemas, tidak memiliki keluarga di sekitar lokasi dan butuh penanganan segera.',
            'reported_at' => now()->subDays(4),
            'officer_id' => $petugasRehsos->id,
            'status' => ComplaintStatus::IN_HANDLING,
            'verification_result' => 'Laporan valid. Tim Reaksi Cepat Dinsos bersama Pemdes Tlogo telah mendatangi lokasi dan mengevakuasi klien.',
            'action_taken' => 'Klien telah dievakuasi ke RSUD dan selanjutnya diteruskan menjadi kasus Rehabilitasi Sosial.',
        ]);

        ComplaintAttachment::create([
            'complaint_id' => $complaint->id,
            'file_path' => 'complaints/lansia-terlantar-tlogo.jpg',
            'type' => ComplaintAttachmentType::PHOTO,
        ]);

        // Disposisi dari pimpinan / admin ke Bidang Rehsos
        Disposition::create([
            'dispositionable_type' => Complaint::class,
            'dispositionable_id' => $complaint->id,
            'from_user_id' => $admin->id,
            'to_work_unit_id' => $rehsosUnit->id,
            'to_user_id' => $petugasRehsos->id,
            'instructions' => 'Segera terjunkan tim penjangkauan untuk evakuasi dan assessment kondisi klien hari ini juga.',
            'disposed_at' => now()->subDays(4)->addHours(2),
        ]);

        // =========================================================================
        // 5. TRANSAKSI LAYANAN 3: KASUS REHABILITASI SOSIAL (Berasal dari Pengaduan)
        // =========================================================================
        $catLansia = ClientCategory::where('name', 'like', '%Lanjut Usia%')->first()
            ?? ClientCategory::first();

        $client = Client::create([
            'name' => 'Mbah Sarinem',
            'client_category_id' => $catLansia->id,
            'nik' => '3505014101490001',
            'birth_date' => '1949-01-01',
            'gender' => 'P',
            'address' => 'Dusun Tlogo, Desa Tlogo, Kec. Kanigoro',
            'village_id' => $tlogo->id,
            'phone' => null,
        ]);

        $caseNumber = NumberSequence::getNextNumber('RHS', $currentPeriod);
        $rehabCase = RehabilitationCase::create([
            'case_number' => $caseNumber,
            'client_id' => $client->id,
            'complaint_id' => $complaint->id, // Terhubung ke laporan asal
            'officer_id' => $petugasRehsos->id,
            'handling_type' => RehabilitationHandlingType::BOTH,
            'status' => RehabilitationCaseStatus::IN_SERVICE,
            'received_at' => now()->subDays(3),
        ]);

        // Assessment Klien
        $assessment = Assessment::create([
            'rehabilitation_case_id' => $rehabCase->id,
            'officer_id' => $petugasRehsos->id,
            'assessment_date' => now()->subDays(3)->toDateString(),
            'result' => 'Klien berusia 77 tahun, fisik lemah karena malnutrisi dan anemia, tidak ada sanak saudara yang mampu merawat di Blitar.',
            'service_needs' => 'Perawatan medis geriatri dan penampungan permanen di Panti Lansia.',
            'recommendation' => 'Pemulihan medis di RSUD Srengat kemudian dirujuk ke UPT PSTW Blitar.',
            'needs_referral' => true,
        ]);

        // Rujukan ke Lembaga
        $pstw = ReferralInstitution::where('name', 'like', '%PSTW%')->first()
            ?? ReferralInstitution::first();

        $referralNumber = NumberSequence::getNextNumber('RJK', $currentPeriod);
        $referral = Referral::create([
            'referral_number' => $referralNumber,
            'rehabilitation_case_id' => $rehabCase->id,
            'assessment_id' => $assessment->id,
            'referral_institution_id' => $pstw->id,
            'officer_id' => $petugasRehsos->id,
            'referral_date' => now()->subDays(1)->toDateString(),
            'status' => ReferralStatus::ACCEPTED,
            'service_result' => 'Klien telah diterima dan menempati asrama Melati UPT PSTW Blitar.',
        ]);

        // Monitoring Perkembangan
        MonitoringRecord::create([
            'rehabilitation_case_id' => $rehabCase->id,
            'referral_id' => $referral->id,
            'officer_id' => $petugasRehsos->id,
            'monitoring_date' => now()->toDateString(),
            'progress' => 'Kondisi kesehatan klien membaik, sudah dapat makan secara mandiri dan mulai berbaur dengan warga panti lainnya.',
            'result_notes' => 'Akan dijadwalkan kunjungan monitoring lanjutan minggu depan.',
        ]);
    }
}
