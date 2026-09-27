<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Orang Terlantar / Gelandangan di Fasilitas Umum',
            'ODGJ Mengamuk / Mengganggu Ketertiban / Terlantar',
            'Lanjut Usia Sebatang Kara Sakit / Terlantar',
            'Penelantaran Anak / Dugaan Kekerasan Terhadap Anak',
            'Dugaan Bantuan Sosial (PKH/BPNT/PBI) Tidak Tepat Sasaran',
            'Penyandang Disabilitas Berat Membutuhkan Alat Bantu / Perawatan',
            'Korban Bencana Sosial / Kebakaran Membutuhkan Bantuan Darurat',
            'Pengaduan Permasalahan Kesejahteraan Sosial Lainnya',
        ];

        foreach ($categories as $cat) {
            ComplaintCategory::firstOrCreate(
                ['name' => $cat],
                ['is_active' => true]
            );
        }
    }
}
