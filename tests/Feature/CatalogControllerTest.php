<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_endpoint_ketersediaan_mengembalikan_daftar_slot(): void
    {
        $court = $this->makeCourt(120000);
        $tanggal = now()->addDay()->toDateString();

        $response = $this->getJson('/api/courts/'.$court->id.'/availability?date='.$tanggal);

        $response->assertOk()
            ->assertJsonPath('court_id', $court->id)
            ->assertJsonPath('date', $tanggal)
            ->assertJsonPath('open_time', '08:00')
            ->assertJsonPath('close_time', '23:00')
            ->assertJsonPath('available_count', 15)
            ->assertJsonPath('slots.0.status', 'available');
    }

    public function test_endpoint_ketersediaan_wajib_menerima_tanggal(): void
    {
        $court = $this->makeCourt();

        $this->getJson('/api/courts/'.$court->id.'/availability')
            ->assertStatus(422)
            ->assertJsonValidationErrors('date');
    }

    public function test_endpoint_ketersediaan_menolak_tanggal_yang_sudah_lewat(): void
    {
        $court = $this->makeCourt();
        $tanggalLewat = now()->subDay()->toDateString();

        $this->getJson('/api/courts/'.$court->id.'/availability?date='.$tanggalLewat)
            ->assertStatus(422)
            ->assertJsonValidationErrors('date');
    }

    public function test_halaman_detail_lapangan_dapat_dibuka_melalui_slug(): void
    {
        $court = $this->makeCourt();

        $response = $this->get('/courts/'.$court->slug);

        $response->assertOk();
        $this->assertTrue($response->viewData('court')->is($court));
    }

    public function test_halaman_daftar_lapangan_hanya_menampilkan_lapangan_aktif(): void
    {
        $aktif = $this->makeCourt();
        $nonaktif = $this->makeCourt();
        $nonaktif->update(['is_active' => false]);

        $response = $this->get('/courts');

        $response->assertOk();

        $courts = $response->viewData('courts');
        $this->assertCount(1, $courts);
        $this->assertTrue($courts->first()->is($aktif));
    }
}
