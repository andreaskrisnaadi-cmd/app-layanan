<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            RegionSeeder::class,
            WorkUnitSeeder::class,
            UserSeeder::class,
            ServiceTypeSeeder::class,
            DtsenPurposeSeeder::class,
            RehabilitationMasterSeeder::class,
            ComplaintCategorySeeder::class,
            InformationPageSeeder::class,
            DemoTransactionSeeder::class,
        ]);
    }
}
