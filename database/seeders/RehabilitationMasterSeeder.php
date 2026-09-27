<?php

namespace Database\Seeders;

use App\Models\ClientCategory;
use App\Models\ReferralInstitution;
use Illuminate\Database\Seeder;

class RehabilitationMasterSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Lanjut Usia Terlantar',
            'Penyandang Disabilitas Fisik',
            'Penyandang Disabilitas Sensorik (Netra / Rungu / Wicara)',
            'Penyandang Disabilitas Intelektual / Mental (ODGJ Terlantar)',
            'Anak Terlantar / Anak Memerlukan Perlindungan Khusus (AMPK)',
            'Korban Tindak Kekerasan / Pekerja Migran Terlantar',
            'Gelandangan, Pengemis, dan Orang Terlantar (PGOT)',
        ];

        foreach ($categories as $categoryName) {
            ClientCategory::firstOrCreate(['name' => $categoryName]);
        }

        $institutions = [
            [
                'name' => 'RSUD Ngudi Waluyo Wlingi',
                'type' => 'RSUD',
                'address' => 'Jl. Dokter Suwandhi No.5, Wlingi, Kabupaten Blitar',
                'contact' => '(0342) 691006',
                'is_active' => true,
            ],
            [
                'name' => 'RSUD Srengat Kabupaten Blitar',
                'type' => 'RSUD',
                'address' => 'Jl. Raya Dandong No.1, Srengat, Kabupaten Blitar',
                'contact' => '(0342) 5651111',
                'is_active' => true,
            ],
            [
                'name' => 'RSJ Menur Surabaya',
                'type' => 'RSJ',
                'address' => 'Jl. Raya Menur No.120, Kertajaya, Kec. Gubeng, Kota Surabaya',
                'contact' => '(031) 5021635',
                'is_active' => true,
            ],
            [
                'name' => 'RSJ Dr. Radjiman Wediodiningrat Lawang',
                'type' => 'RSJ',
                'address' => 'Jl. Jend. A. Yani, Lawang, Kabupaten Malang',
                'contact' => '(0341) 426015',
                'is_active' => true,
            ],
            [
                'name' => 'UPT Pelayanan Sosial Tresna Werdha (PSTW) Blitar',
                'type' => 'Panti',
                'address' => 'Jl. Raya Dandong, Srengat, Kabupaten Blitar',
                'contact' => '(0342) 551234',
                'is_active' => true,
            ],
            [
                'name' => 'UPT Rehabilitasi Sosial Bina Netra (RSBN) Malang',
                'type' => 'Balai',
                'address' => 'Jl. Raya Badut No.1, Kota Malang',
                'contact' => '(0341) 560123',
                'is_active' => true,
            ],
            [
                'name' => 'Sentra Terpadu Prof. Dr. Soeharso Surakarta',
                'type' => 'Balai',
                'address' => 'Jl. Tentara Pelajar, Jebres, Kota Surakarta',
                'contact' => '(0271) 646123',
                'is_active' => true,
            ],
            [
                'name' => 'LKS Mitra Mandiri Blitar',
                'type' => 'LKS',
                'address' => 'Jl. Raya Kanigoro, Kabupaten Blitar',
                'contact' => '081233445566',
                'is_active' => true,
            ],
        ];

        foreach ($institutions as $inst) {
            ReferralInstitution::firstOrCreate(['name' => $inst['name']], $inst);
        }
    }
}
