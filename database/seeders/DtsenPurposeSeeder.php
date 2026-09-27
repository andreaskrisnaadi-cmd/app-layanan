<?php

namespace Database\Seeders;

use App\Models\DtsenPurpose;
use Illuminate\Database\Seeder;

class DtsenPurposeSeeder extends Seeder
{
    public function run(): void
    {
        $purposes = [
            [
                'code' => 'spmb',
                'name' => 'SPMB Jalur Afirmasi (SD / SMP / SMA / SMK)',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'pip',
                'name' => 'Program Indonesia Pintar (PIP)',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'kip_kuliah',
                'name' => 'KIP Kuliah (Perguruan Tinggi)',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'bansos',
                'name' => 'Pengusulan Bantuan Sosial (PKH / Sembako / RST)',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'kesehatan',
                'name' => 'Keringanan Biaya Pelayanan Kesehatan Rumah Sakit',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'lainnya',
                'name' => 'Keperluan Administrasi Sosial Lainnya',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($purposes as $purpose) {
            DtsenPurpose::firstOrCreate(['code' => $purpose['code']], $purpose);
        }
    }
}
