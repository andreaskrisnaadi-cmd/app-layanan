<?php

namespace Tests\Feature;

use App\Enums\ApprovalDecision;
use App\Enums\ComplaintStatus;
use App\Enums\DocumentVerificationStatus;
use App\Enums\InformationCategory;
use App\Enums\MinistryDecision;
use App\Enums\PbiReason;
use App\Enums\PublishStatus;
use App\Enums\ReferralStatus;
use App\Enums\RehabilitationCaseStatus;
use App\Enums\RehabilitationHandlingType;
use App\Enums\ServiceHandler;
use App\Enums\ServiceRequestStatus;
use App\Models\Approval;
use App\Models\Assessment;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\ComplaintAttachment;
use App\Models\ComplaintCategory;
use App\Models\Disposition;
use App\Models\District;
use App\Models\DownloadableForm;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
use App\Models\Faq;
use App\Models\InformationPage;
use App\Models\MonitoringRecord;
use App\Models\NumberSequence;
use App\Models\PageVisit;
use App\Models\PbiReactivation;
use App\Models\Referral;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use App\Models\SearchLog;
use App\Models\ServiceRequirement;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceType;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ModelStructureTest extends TestCase
{
    use DatabaseTransactions;
    public function test_enums_have_valid_labels(): void
    {
        $this->assertEquals('Diajukan', ServiceRequestStatus::SUBMITTED->label());
        $this->assertEquals('Sesuai / Valid', DocumentVerificationStatus::VALID->label());
        $this->assertEquals('Disetujui / Diparaf', ApprovalDecision::APPROVED->label());
        $this->assertEquals('Kondisi Darurat Medis', PbiReason::EMERGENCY->label());
        $this->assertEquals('Disetujui Kemensos', MinistryDecision::APPROVED->label());
        $this->assertEquals('Pelayanan Langsung', RehabilitationHandlingType::DIRECT->label());
        $this->assertEquals('Kasus Diterima', RehabilitationCaseStatus::RECEIVED->label());
        $this->assertEquals('Terkirim ke Lembaga', ReferralStatus::SENT->label());
        $this->assertEquals('Laporan Diterima', ComplaintStatus::RECEIVED->label());
        $this->assertEquals('Program Sosial', InformationCategory::PROGRAM->label());
        $this->assertEquals('Diterbitkan', PublishStatus::PUBLISHED->label());
        $this->assertEquals('Surat Keterangan DTSEN', ServiceHandler::DTSEN->label());
    }

    public function test_number_sequence_generation(): void
    {
        $prefix = 'TEST' . rand(100, 999);
        $period = '202610';

        $seq1 = NumberSequence::getNextNumber($prefix, $period);
        $this->assertEquals("{$prefix}-{$period}-00001", $seq1);

        $seq2 = NumberSequence::getNextNumber($prefix, $period);
        $this->assertEquals("{$prefix}-{$period}-00002", $seq2);
    }

    public function test_models_can_be_queried(): void
    {
        $this->assertIsInt(WorkUnit::count());
        $this->assertIsInt(District::count());
        $this->assertIsInt(Village::count());
        $this->assertIsInt(User::count());
        $this->assertIsInt(ServiceType::count());
        $this->assertIsInt(ServiceRequirement::count());
        $this->assertIsInt(ServiceRequest::count());
        $this->assertIsInt(ServiceRequestDocument::count());
        $this->assertIsInt(DtsenPurpose::count());
        $this->assertIsInt(DtsenCertificate::count());
        $this->assertIsInt(PbiReactivation::count());
        $this->assertIsInt(Approval::count());
        $this->assertIsInt(ComplaintCategory::count());
        $this->assertIsInt(Complaint::count());
        $this->assertIsInt(ComplaintAttachment::count());
        $this->assertIsInt(ClientCategory::count());
        $this->assertIsInt(Client::count());
        $this->assertIsInt(RehabilitationCase::count());
        $this->assertIsInt(Assessment::count());
        $this->assertIsInt(ReferralInstitution::count());
        $this->assertIsInt(Referral::count());
        $this->assertIsInt(MonitoringRecord::count());
        $this->assertIsInt(InformationPage::count());
        $this->assertIsInt(DownloadableForm::count());
        $this->assertIsInt(Faq::count());
        $this->assertIsInt(PageVisit::count());
        $this->assertIsInt(SearchLog::count());
        $this->assertIsInt(StatusHistory::count());
        $this->assertIsInt(Disposition::count());
    }
}
