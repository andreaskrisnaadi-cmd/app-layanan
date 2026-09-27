<?php

namespace Database\Seeders;

use App\Enums\ServiceHandler;
use App\Models\ServiceRequirement;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'code' => 'DTSEN',
                'name' => 'Surat Keterangan DTSEN',
                'category' => 'Layanan Administrasi Sosial',
                'description' => 'Penerbitan surat keterangan yang menerangkan status seseorang/keluarga dalam Data Tunggal Sosial Ekonomi Nasional (DTSEN), termasuk peringkat desil untuk keperluan SPMB afirmasi, PIP, KIP Kuliah, bansos, dan kesehatan.',
                'handler' => ServiceHandler::DTSEN,
                'needs_assessment' => false,
                'sla_days' => 3,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Pemohon',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'KTP / Identitas Orang yang Diterangkan (jika berbeda dari pemohon)',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'code' => 'PBI',
                'name' => 'Reaktivasi KIS / PBI-JK',
                'category' => 'Layanan Jaminan Kesehatan',
                'description' => 'Fasilitasi pengaktifan kembali kepesertaan JKN-KIS Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) yang dinonaktifkan melalui verifikasi kelayakan dan pengusulan ke Kementerian Sosial via SIKS-NG.',
                'handler' => ServiceHandler::PBI,
                'needs_assessment' => false,
                'sla_days' => 14,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Peserta / Pemohon',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Kartu BPJS Kesehatan / KIS yang Dinonaktifkan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Surat Keterangan Rawat Inap / Rujukan Faskes (wajib untuk kondisi darurat/sakit kronis)',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'code' => 'REHSOS',
                'name' => 'Permohonan Pelayanan Rehabilitasi Sosial',
                'category' => 'Rehabilitasi Sosial',
                'description' => 'Permohonan penanganan rehabilitasi sosial bagi penyandang disabilitas, lansia terlantar, ODGJ terlantar, dan anak memerlukan perlindungan khusus untuk mendapatkan pelayanan langsung atau rujukan.',
                'handler' => ServiceHandler::GENERIC,
                'needs_assessment' => true,
                'sla_days' => 7,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP / Identitas Klien atau Pelapor (jika ada)',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Foto Kondisi Klien Saat Ini',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'jpg,jpeg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Surat Pengantar dari Pemerintah Desa / Kelurahan',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'code' => 'BANSOS',
                'name' => 'Rekomendasi Bantuan Sosial Terencana / Insidental',
                'category' => 'Bantuan Sosial',
                'description' => 'Pengajuan rekomendasi bantuan sosial terencana maupun bantuan insidental bagi warga pemerlu pelayanan kesejahteraan sosial.',
                'handler' => ServiceHandler::GENERIC,
                'needs_assessment' => true,
                'sla_days' => 5,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Pemohon',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Surat Keterangan Tidak Mampu (SKTM) dari Desa/Kelurahan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,jpeg,png',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Foto Rumah Tampak Depan dan Ruang Tamu',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'jpg,jpeg,png',
                        'sort_order' => 4,
                    ],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $requirements = $serviceData['requirements'];
            unset($serviceData['requirements']);

            $service = ServiceType::firstOrCreate(
                ['code' => $serviceData['code']],
                $serviceData
            );

            foreach ($requirements as $reqData) {
                ServiceRequirement::firstOrCreate(
                    [
                        'service_type_id' => $service->id,
                        'name' => $reqData['name'],
                    ],
                    $reqData
                );
            }
        }
    }
}
