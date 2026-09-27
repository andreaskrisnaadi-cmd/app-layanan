<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentResourcesTest extends TestCase
{
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        if (! \Spatie\Permission\Models\Role::where('name', 'Administrator')->exists()) {
            $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
        }

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin@sapasosial.blitarkab.go.id'],
            [
                'name' => 'Administrator Test',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );

        if (! $this->adminUser->hasRole('Administrator')) {
            $this->adminUser->assignRole('Administrator');
        }
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/service-requests');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_master_data_resources(): void
    {
        $endpoints = [
            '/admin/districts',
            '/admin/villages',
            '/admin/work-units',
            '/admin/service-types',
            '/admin/dtsen-purposes',
            '/admin/complaint-categories',
            '/admin/client-categories',
            '/admin/referral-institutions',
            '/admin/users',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->actingAs($this->adminUser)->get($endpoint);
            $response->assertSuccessful("Failed accessing endpoint: {$endpoint}");
        }
    }

    public function test_admin_can_access_service_and_case_resources(): void
    {
        $endpoints = [
            '/admin/service-requests',
            '/admin/rehabilitation-cases',
            '/admin/complaints',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->actingAs($this->adminUser)->get($endpoint);
            $response->assertSuccessful("Failed accessing endpoint: {$endpoint}");
        }
    }
}
