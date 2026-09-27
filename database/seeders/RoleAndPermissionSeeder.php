<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // Dashboard & Reports
            'view-dashboard',
            'view-reports',
            'export-reports',

            // Service Requests
            'view-service-requests',
            'create-service-requests',
            'verify-service-requests',
            'process-service-requests',
            'approve-service-requests',
            'sign-service-requests',

            // DTSEN & PBI Details
            'manage-dtsen-certificates',
            'verify-siksng',
            'manage-pbi-reactivations',

            // Rehabilitation
            'view-rehabilitation-cases',
            'create-rehabilitation-cases',
            'manage-assessments',
            'manage-referrals',
            'manage-monitoring',

            // Complaints
            'view-complaints',
            'create-complaints',
            'verify-complaints',
            'handle-complaints',
            'dispatch-complaints',

            // Master Data & Content
            'manage-master-data',
            'manage-information-pages',
            'manage-users',
            'view-audit-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 1. Administrator: Akses penuh ke seluruh sistem
        $adminRole = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // 2. Petugas Dinsos: Memeriksa, verifikasi, asesmen, penanganan, monitoring, disposisi
        $officerRole = Role::firstOrCreate(['name' => 'Petugas Dinsos', 'guard_name' => 'web']);
        $officerRole->syncPermissions([
            'view-dashboard',
            'view-reports',
            'export-reports',
            'view-service-requests',
            'verify-service-requests',
            'process-service-requests',
            'manage-dtsen-certificates',
            'verify-siksng',
            'manage-pbi-reactivations',
            'view-rehabilitation-cases',
            'create-rehabilitation-cases',
            'manage-assessments',
            'manage-referrals',
            'manage-monitoring',
            'view-complaints',
            'verify-complaints',
            'handle-complaints',
            'dispatch-complaints',
            'manage-information-pages',
        ]);

        // 3. Pejabat Penandatangan: Memeriksa (paraf) & menyetujui SK DTSEN / Rekomendasi PBI
        $signerRole = Role::firstOrCreate(['name' => 'Pejabat Penandatangan', 'guard_name' => 'web']);
        $signerRole->syncPermissions([
            'view-dashboard',
            'view-reports',
            'view-service-requests',
            'approve-service-requests',
            'sign-service-requests',
            'manage-dtsen-certificates',
            'manage-pbi-reactivations',
            'view-rehabilitation-cases',
            'view-complaints',
        ]);

        // 4. Pimpinan: Kepala Dinas / Pejabat terkait — hanya baca dashboard & laporan
        $leaderRole = Role::firstOrCreate(['name' => 'Pimpinan', 'guard_name' => 'web']);
        $leaderRole->syncPermissions([
            'view-dashboard',
            'view-reports',
            'export-reports',
            'view-service-requests',
            'view-rehabilitation-cases',
            'view-complaints',
        ]);

        // 5. Operator Kecamatan/Desa: Membantu warga mengajukan & memantau di wilayahnya
        $operatorRole = Role::firstOrCreate(['name' => 'Operator Kecamatan/Desa', 'guard_name' => 'web']);
        $operatorRole->syncPermissions([
            'view-dashboard',
            'view-service-requests',
            'create-service-requests',
            'view-complaints',
            'create-complaints',
        ]);

        // 6. Masyarakat: Mengajukan layanan & pengaduan
        $citizenRole = Role::firstOrCreate(['name' => 'Masyarakat', 'guard_name' => 'web']);
        $citizenRole->syncPermissions([
            'create-service-requests',
            'create-complaints',
        ]);
    }
}
