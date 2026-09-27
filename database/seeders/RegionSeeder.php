<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'code' => '35.05.01',
                'name' => 'Kecamatan Kanigoro',
                'villages' => [
                    ['code' => '35.05.01.1001', 'name' => 'Kelurahan Kanigoro'],
                    ['code' => '35.05.01.1002', 'name' => 'Kelurahan Satreyan'],
                    ['code' => '35.05.01.2003', 'name' => 'Desa Tlogo'],
                    ['code' => '35.05.01.2004', 'name' => 'Desa Gaprang'],
                    ['code' => '35.05.01.2005', 'name' => 'Desa Gogodeso'],
                    ['code' => '35.05.01.2006', 'name' => 'Desa Jatinom'],
                    ['code' => '35.05.01.2007', 'name' => 'Desa Sawentar'],
                    ['code' => '35.05.01.2008', 'name' => 'Desa Papungan'],
                    ['code' => '35.05.01.2009', 'name' => 'Desa Karangsono'],
                    ['code' => '35.05.01.2010', 'name' => 'Desa Bangle'],
                ],
            ],
            [
                'code' => '35.05.02',
                'name' => 'Kecamatan Garum',
                'villages' => [
                    ['code' => '35.05.02.1001', 'name' => 'Kelurahan Garum'],
                    ['code' => '35.05.02.1002', 'name' => 'Kelurahan Bence'],
                    ['code' => '35.05.02.1003', 'name' => 'Kelurahan Tawangsari'],
                    ['code' => '35.05.02.1004', 'name' => 'Kelurahan Sumberdiren'],
                    ['code' => '35.05.02.2005', 'name' => 'Desa Slorok'],
                    ['code' => '35.05.02.2006', 'name' => 'Desa Pojok'],
                    ['code' => '35.05.02.2007', 'name' => 'Desa Tingal'],
                    ['code' => '35.05.02.2008', 'name' => 'Desa Karangrejo'],
                    ['code' => '35.05.02.2009', 'name' => 'Desa Sidodadi'],
                ],
            ],
            [
                'code' => '35.05.03',
                'name' => 'Kecamatan Wlingi',
                'villages' => [
                    ['code' => '35.05.03.1001', 'name' => 'Kelurahan Wlingi'],
                    ['code' => '35.05.03.1002', 'name' => 'Kelurahan Beru'],
                    ['code' => '35.05.03.1003', 'name' => 'Kelurahan Babadan'],
                    ['code' => '35.05.03.1004', 'name' => 'Kelurahan Klemunan'],
                    ['code' => '35.05.03.1005', 'name' => 'Kelurahan Tangkil'],
                    ['code' => '35.05.03.2006', 'name' => 'Desa Ngadirenggo'],
                    ['code' => '35.05.03.2007', 'name' => 'Desa Tegalasri'],
                    ['code' => '35.05.03.2008', 'name' => 'Desa Balerejo'],
                    ['code' => '35.05.03.2009', 'name' => 'Desa Tembalang'],
                ],
            ],
            [
                'code' => '35.05.04',
                'name' => 'Kecamatan Talun',
                'villages' => [
                    ['code' => '35.05.04.1001', 'name' => 'Kelurahan Kamulan'],
                    ['code' => '35.05.04.1002', 'name' => 'Kelurahan Kaweron'],
                    ['code' => '35.05.04.1003', 'name' => 'Kelurahan Bajang'],
                    ['code' => '35.05.04.1004', 'name' => 'Kelurahan Talun'],
                    ['code' => '35.05.04.2005', 'name' => 'Desa Bendosewu'],
                    ['code' => '35.05.04.2006', 'name' => 'Desa Duren'],
                    ['code' => '35.05.04.2007', 'name' => 'Desa Jabung'],
                    ['code' => '35.05.04.2008', 'name' => 'Desa Jajar'],
                    ['code' => '35.05.04.2009', 'name' => 'Desa Kendalrejo'],
                    ['code' => '35.05.04.2010', 'name' => 'Desa Pasirharjo'],
                    ['code' => '35.05.04.2011', 'name' => 'Desa Sragi'],
                    ['code' => '35.05.04.2012', 'name' => 'Desa Tumpang'],
                    ['code' => '35.05.04.2013', 'name' => 'Desa Wonorejo'],
                ],
            ],
            [
                'code' => '35.05.05',
                'name' => 'Kecamatan Sutojayan',
                'villages' => [
                    ['code' => '35.05.05.1001', 'name' => 'Kelurahan Sutojayan'],
                    ['code' => '35.05.05.1002', 'name' => 'Kelurahan Kalipang'],
                    ['code' => '35.05.05.1003', 'name' => 'Kelurahan Kembangarum'],
                    ['code' => '35.05.05.1004', 'name' => 'Kelurahan Kedungbunder'],
                    ['code' => '35.05.05.1005', 'name' => 'Kelurahan Sukorejo'],
                    ['code' => '35.05.05.2006', 'name' => 'Desa Pandanarum'],
                    ['code' => '35.05.05.2007', 'name' => 'Desa Bacem'],
                    ['code' => '35.05.05.2008', 'name' => 'Desa Kaulon'],
                ],
            ],
            [
                'code' => '35.05.06',
                'name' => 'Kecamatan Nglegok',
                'villages' => [
                    ['code' => '35.05.06.1001', 'name' => 'Kelurahan Nglegok'],
                    ['code' => '35.05.06.2002', 'name' => 'Desa Modangan'],
                    ['code' => '35.05.06.2003', 'name' => 'Desa Ngoran'],
                    ['code' => '35.05.06.2004', 'name' => 'Desa Penataran'],
                    ['code' => '35.05.06.2005', 'name' => 'Desa Jiwut'],
                    ['code' => '35.05.06.2006', 'name' => 'Desa Kedawung'],
                    ['code' => '35.05.06.2007', 'name' => 'Desa Dayu'],
                    ['code' => '35.05.06.2008', 'name' => 'Desa Sumberasri'],
                    ['code' => '35.05.06.2009', 'name' => 'Desa Bangsri'],
                    ['code' => '35.05.06.2010', 'name' => 'Desa Krenceng'],
                ],
            ],
            [
                'code' => '35.05.07',
                'name' => 'Kecamatan Srengat',
                'villages' => [
                    ['code' => '35.05.07.1001', 'name' => 'Kelurahan Srengat'],
                    ['code' => '35.05.07.1002', 'name' => 'Kelurahan Dandong'],
                    ['code' => '35.05.07.1003', 'name' => 'Kelurahan Kauman'],
                    ['code' => '35.05.07.1004', 'name' => 'Kelurahan Togogan'],
                    ['code' => '35.05.07.2005', 'name' => 'Desa Bagelenan'],
                    ['code' => '35.05.07.2006', 'name' => 'Desa Dermojayan'],
                    ['code' => '35.05.07.2007', 'name' => 'Desa Kandangan'],
                    ['code' => '35.05.07.2008', 'name' => 'Desa Karanggayam'],
                    ['code' => '35.05.07.2009', 'name' => 'Desa Maron'],
                    ['code' => '35.05.07.2010', 'name' => 'Desa Ngaglik'],
                    ['code' => '35.05.07.2011', 'name' => 'Desa Pakisrejo'],
                    ['code' => '35.05.07.2012', 'name' => 'Desa Purwokerto'],
                    ['code' => '35.05.07.2013', 'name' => 'Desa Selokajang'],
                    ['code' => '35.05.07.2014', 'name' => 'Desa Wonorejo'],
                ],
            ],
            [
                'code' => '35.05.08',
                'name' => 'Kecamatan Sanankulon',
                'villages' => [
                    ['code' => '35.05.08.2001', 'name' => 'Desa Sanankulon'],
                    ['code' => '35.05.08.2002', 'name' => 'Desa Bendowulung'],
                    ['code' => '35.05.08.2003', 'name' => 'Desa Kalipucang'],
                    ['code' => '35.05.08.2004', 'name' => 'Desa Plosoarang'],
                    ['code' => '35.05.08.2005', 'name' => 'Desa Purworejo'],
                    ['code' => '35.05.08.2006', 'name' => 'Desa Sumber'],
                    ['code' => '35.05.08.2007', 'name' => 'Desa Sumberjo'],
                    ['code' => '35.05.08.2008', 'name' => 'Desa Gleduk'],
                    ['code' => '35.05.08.2009', 'name' => 'Desa Tuliskriyo'],
                ],
            ],
        ];

        foreach ($regions as $districtData) {
            $villages = $districtData['villages'];
            unset($districtData['villages']);

            $district = District::firstOrCreate(
                ['code' => $districtData['code']],
                ['name' => $districtData['name']]
            );

            foreach ($villages as $villageData) {
                Village::firstOrCreate(
                    ['code' => $villageData['code']],
                    [
                        'district_id' => $district->id,
                        'name' => $villageData['name'],
                    ]
                );
            }
        }
    }
}
