<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class AdminRouteExposureTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_route_show_yang_tidak_digunakan_mengembalikan_404(): void
    {
        $court = $this->makeCourt();
        $superadmin = $this->makeCustomer(['role' => 'superadmin']);

        $this->actingAs($superadmin)
            ->get('/admin/courts/'.$court->id)
            ->assertStatus(405);

        $this->actingAs($superadmin)
            ->get('/admin/branches/'.$court->branch_id)
            ->assertStatus(405);

        $this->actingAs($superadmin)
            ->get('/admin/users/'.$superadmin->id)
            ->assertStatus(405);

        $this->actingAs($superadmin)
            ->get('/admin/facilities/1')
            ->assertStatus(405);
    }

    public function test_halaman_index_admin_tetap_dapat_diakses(): void
    {
        $court = $this->makeCourt();
        $superadmin = $this->makeCustomer(['role' => 'superadmin']);

        $this->actingAs($superadmin)->get(route('admin.courts.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.branches.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($superadmin)->get(route('admin.facilities.index'))->assertOk();
    }
}
