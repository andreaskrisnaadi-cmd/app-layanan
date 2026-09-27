<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $linjamsos = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsos = WorkUnit::where('name', 'like', '%Rehsos%')->first();
        $sekretariat = WorkUnit::where('name', 'like', '%Sekretariat%')->first();

        $kanigoroDistrict = District::where('name', 'like', '%Kanigoro%')->first();
        $tlogoVillage = Village::where('name', 'like', '%Tlogo%')->first();
        $satreyanVillage = Village::where('name', 'like', '%Satreyan%')->first();

        // 1. Administrator
        $admin = User::firstOrCreate(
            ['email' => 'admin@dinsos.blitarkab.go.id'],
            [
                'name' => 'Ahmad Muamar Muzakki',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'nik' => '3505011508920001',
                'work_unit_id' => $sekretariat?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['Administrator']);

        // 2. Petugas Pelayanan Dinsos (Linjamsos - DTSEN & PBI)
        $petugasLinjamsos = User::firstOrCreate(
            ['email' => 'petugas.pelayanan@dinsos.blitarkab.go.id'],
            [
                'name' => 'Dewi Lestari, S.Sos',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'nik' => '3505015204890002',
                'work_unit_id' => $linjamsos?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $petugasLinjamsos->syncRoles(['Petugas Dinsos']);

        // 3. Petugas Rehabilitasi Sosial
        $petugasRehsos = User::firstOrCreate(
            ['email' => 'petugas.rehsos@dinsos.blitarkab.go.id'],
            [
                'name' => 'Agus Priyono, S.Tr.Sos',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'nik' => '3505011907870003',
                'work_unit_id' => $rehsos?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $petugasRehsos->syncRoles(['Petugas Dinsos']);

        // 4. Pejabat Penandatangan: Kepala Bidang Linjamsos (Paraf Step 1)
        $kabid = User::firstOrCreate(
            ['email' => 'kabid.linjamsos@dinsos.blitarkab.go.id'],
            [
                'name' => 'Drs. Supriyadi, M.Si',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'nik' => '3505011102720004',
                'work_unit_id' => $linjamsos?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $kabid->syncRoles(['Pejabat Penandatangan']);

        // 5. Kepala Dinas (Pimpinan + Pejabat Penandatangan Step 2)
        $kadis = User::firstOrCreate(
            ['email' => 'kadis@dinsos.blitarkab.go.id'],
            [
                'name' => 'Bambang Hermanto, SH, MM',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'nik' => '3505010506680005',
                'work_unit_id' => $sekretariat?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $kadis->syncRoles(['Pejabat Penandatangan', 'Pimpinan']);

        // 6. Operator Kecamatan Kanigoro
        $operatorKec = User::firstOrCreate(
            ['email' => 'operator.kanigoro@blitarkab.go.id'],
            [
                'name' => 'Rina Wahyuni (Operator Kec. Kanigoro)',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'nik' => '3505016010940006',
                'district_id' => $kanigoroDistrict?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $operatorKec->syncRoles(['Operator Kecamatan/Desa']);

        // 7. Operator Desa Tlogo
        $operatorDesa = User::firstOrCreate(
            ['email' => 'operator.tlogo@blitarkab.go.id'],
            [
                'name' => 'Hendra Setiawan (Operator Desa Tlogo)',
                'password' => Hash::make('password'),
                'phone' => '081234567896',
                'nik' => '3505012503930007',
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $tlogoVillage?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $operatorDesa->syncRoles(['Operator Kecamatan/Desa']);

        // 8. Warga / Masyarakat
        $wargaBudi = User::firstOrCreate(
            ['email' => 'warga.budi@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'phone' => '085712345678',
                'nik' => '3505011205850001',
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $satreyanVillage?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $wargaBudi->syncRoles(['Masyarakat']);

        $wargaSiti = User::firstOrCreate(
            ['email' => 'warga.siti@example.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'phone' => '085787654321',
                'nik' => '3505015508900002',
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $tlogoVillage?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $wargaSiti->syncRoles(['Masyarakat']);
    }
}
