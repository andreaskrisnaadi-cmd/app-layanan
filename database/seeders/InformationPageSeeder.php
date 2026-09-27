<?php

namespace Database\Seeders;

use App\Enums\InformationCategory;
use App\Enums\PublishStatus;
use App\Models\DownloadableForm;
use App\Models\Faq;
use App\Models\InformationPage;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformationPageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $dtsenType = ServiceType::where('code', 'DTSEN')->first();
        $pbiType = ServiceType::where('code', 'PBI')->first();
        $rehsosType = ServiceType::where('code', 'REHSOS')->first();

        // 1. Informasi SK DTSEN
        $pageDtsen = InformationPage::firstOrCreate(
            ['slug' => 'surat-keterangan-dtsen'],
            [
                'title' => 'Panduan & Layanan Surat Keterangan DTSEN Kabupaten Blitar',
                'category' => InformationCategory::PROGRAM,
                'service_type_id' => $dtsenType?->id,
                'description' => 'Surat Keterangan Data Tunggal Sosial Ekonomi Nasional (DTSEN) adalah dokumen resmi yang menerangkan status peringkat kesejahteraan (desil 1 s.d. 10) keluarga warga Kabupaten Blitar yang terdaftar di basis data Kemensos RI.',
                'requirements' => "1. Kartu Tanda Penduduk (KTP) Pemohon asli/fotokopi.\n2. Kartu Keluarga (KK) Kabupaten Blitar.\n3. KTP/Kartu Pelajar anggota keluarga yang diterangkan (untuk beasiswa/SPMB).",
                'procedure' => "1. Pemohon mengajukan permohonan online melalui portal SAPA SOSIAL atau melalui Operator Desa/Kecamatan.\n2. Petugas memverifikasi kelengkapan berkas dan mencocokkan data di SIKS-NG.\n3. Petugas meneliti kesesuaian batas desil dengan tujuan penggunaan.\n4. Persetujuan berjenjang oleh Kepala Bidang dan Kepala Dinas Sosial.\n5. Surat diterbitkan secara elektronik dengan QR Code verifikasi keaslian.",
                'service_hours' => 'Senin - Kamis: 08.00 - 15.00 WIB | Jumat: 08.00 - 14.30 WIB',
                'location' => 'Kantor Dinas Sosial Kabupaten Blitar, Jl. Raya Kanigoro, Blitar',
                'contact' => 'WhatsApp Layanan: 0812-3456-7890 | Email: dinsos@blitarkab.go.id',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now(),
                'manager_id' => $admin?->id,
            ]
        );

        DownloadableForm::firstOrCreate(
            ['name' => 'Formulir Permohonan SK DTSEN (Manual)', 'information_page_id' => $pageDtsen->id],
            [
                'file_path' => 'forms/form-permohonan-dtsen-v1.pdf',
                'version' => '1.0',
                'is_current' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'Bagaimana cara mengetahui desil saya di DTSEN?', 'information_page_id' => $pageDtsen->id],
            [
                'answer' => 'Desil Anda akan dicek langsung oleh petugas pelayanan Dinas Sosial Kabupaten Blitar melalui akun SIKS-NG resmi saat Anda mengajukan Surat Keterangan DTSEN di SAPA SOSIAL.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'Berapa lama proses penerbitan Surat Keterangan DTSEN?', 'information_page_id' => $pageDtsen->id],
            [
                'answer' => 'Standar pelayanan (SLA) penerbitan SK DTSEN adalah maksimal 3 (tiga) hari kerja sejak berkas dinyatakan lengkap dan valid.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 2. Informasi Reaktivasi KIS / PBI-JK
        $pagePbi = InformationPage::firstOrCreate(
            ['slug' => 'reaktivasi-kis-pbi-jk'],
            [
                'title' => 'Tata Cara Reaktivasi Kartu KIS / PBI-JK yang Dinonaktifkan',
                'category' => InformationCategory::PROGRAM,
                'service_type_id' => $pbiType?->id,
                'description' => 'Fasilitasi pengusulan pengaktifan kembali kartu BPJS Kesehatan Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) yang dibiayai APBN bagi warga pra-sejahtera Kabupaten Blitar.',
                'requirements' => "1. KTP dan Kartu Keluarga (KK).\n2. Kartu BPJS Kesehatan / KIS yang nonaktif.\n3. Surat Keterangan rawat inap / resume medis dari Fasilitas Kesehatan (wajib untuk kondisi darurat/penyakit kronis).",
                'procedure' => "1. Warga mengajukan melalui portal SAPA SOSIAL dengan melampirkan berkas dan nomor kartu BPJS.\n2. Verifikasi desil dan kelayakan administrasi oleh petugas Dinsos.\n3. Penerbitan surat rekomendasi reaktivasi oleh Kepala Dinas Sosial.\n4. Input usulan ke aplikasi SIKS-NG Kemensos RI.\n5. Menunggu persetujuan Kemensos dan aktivasi data di BPJS Kesehatan.",
                'service_hours' => 'Senin - Jumat (Kondisi darurat medis diprioritaskan)',
                'location' => 'Bidang Linjamsos Dinas Sosial Kabupaten Blitar',
                'contact' => 'Call Center Reaktivasi KIS Blitar: 0812-3456-7891',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now(),
                'manager_id' => $admin?->id,
            ]
        );

        DownloadableForm::firstOrCreate(
            ['name' => 'Blanko Surat Pernyataan Pemohon Reaktivasi KIS', 'information_page_id' => $pagePbi->id],
            [
                'file_path' => 'forms/sptjm-reaktivasi-kis-v1.pdf',
                'version' => '1.0',
                'is_current' => true,
            ]
        );

        Faq::firstOrCreate(
            ['question' => 'Mengapa kartu KIS PBI saya tiba-tiba tidak aktif?', 'information_page_id' => $pagePbi->id],
            [
                'answer' => 'Penonaktifan dapat terjadi karena pemutakhiran data berkala oleh Kementerian Sosial RI berdasarkan perbaikan data kependudukan atau penyesuaian kuota nasional.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 3. Informasi Rehabilitasi Sosial
        $pageRehsos = InformationPage::firstOrCreate(
            ['slug' => 'layanan-rehabilitasi-sosial'],
            [
                'title' => 'Layanan Penanganan Rehabilitasi Sosial Kabupaten Blitar',
                'category' => InformationCategory::REHABILITATION,
                'service_type_id' => $rehsosType?->id,
                'description' => 'Layanan perlindungan, penampungan darurat, assessment, dan rujukan ke balai/panti/RSJ bagi penyandang disabilitas terlantar, lansia sebatang kara, ODGJ, dan anak berhadapan dengan hukum.',
                'requirements' => "1. Laporan warga / desa / kepolisian / relawan sosial.\n2. Foto kondisi klien di lokasi kejadian.\n3. Identitas klien jika ditemukan.",
                'procedure' => "1. Laporan diterima tim reaksi cepat Dinsos.\n2. Assessment awal kondisi fisik, psikososial, dan lingkungan.\n3. Penyusunan rencana pelayanan (pelayanan langsung atau rujukan ke RS/Panti).\n4. Pelaksanaan penanganan & monitoring berkala hingga terminasi kasus.",
                'service_hours' => 'Layanan Pengaduan Darurat Siaga 24 Jam',
                'location' => 'Bidang Rehabilitasi Sosial Dinsos Kabupaten Blitar',
                'contact' => 'Hotline Rehsos Blitar: 0812-9876-5432',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now(),
                'manager_id' => $admin?->id,
            ]
        );

        // FAQ Umum Layanan Dinsos
        Faq::firstOrCreate(
            ['question' => 'Apakah seluruh layanan di SAPA SOSIAL dipungut biaya?', 'information_page_id' => null],
            [
                'answer' => 'TIDAK DIPUNGUT BIAYA (GRATIS). Seluruh layanan sosial di Kabupaten Blitar tidak memungut biaya apapun dari masyarakat.',
                'sort_order' => 99,
                'is_active' => true,
            ]
        );
    }
}
