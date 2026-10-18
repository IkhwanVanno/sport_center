<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\PricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    private PricingService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(PricingService::class);
    }

    public function test_menghitung_biaya_dari_tarif_dasar(): void
    {
        $court = $this->makeCourt(120000);

        $result = $this->service->calculate($court->id, '2026-01-05', [
            ['start_time' => '08:00', 'end_time' => '10:00'],
        ]);

        $this->assertEquals(2.0, $result['items'][0]['hours']);
        $this->assertEquals(240000, $result['total']);
        $this->assertCount(2, $result['items'][0]['breakdown']);
    }

    public function test_menggunakan_tarif_puncak_pada_hari_dan_jam_yang_sesuai(): void
    {
        $court = $this->makeCourt(120000);
        $this->makeRule($court, 1, '18:00', '23:00', 150000);

        $result = $this->service->calculate($court, '2026-01-05', [
            ['start_time' => '17:00', 'end_time' => '19:00'],
        ]);

        $this->assertEquals(120000, $result['items'][0]['breakdown'][0]['price_per_hour']);
        $this->assertEquals(150000, $result['items'][0]['breakdown'][1]['price_per_hour']);
        $this->assertEquals(270000, $result['total']);
    }

    public function test_aturan_tarif_hanya_berlaku_pada_hari_yang_sesuai(): void
    {
        $court = $this->makeCourt(120000);
        $this->makeRule($court, 0, '18:00', '23:00', 150000);

        $hargaMinggu = $this->service->hourlyPrice($court, '2026-01-04', '18:00');
        $hargaSenin = $this->service->hourlyPrice($court, '2026-01-05', '18:00');

        $this->assertEquals(150000, $hargaMinggu);
        $this->assertEquals(120000, $hargaSenin);
    }

    public function test_slot_yang_melewati_perubahan_tarif_dihitung_per_jam(): void
    {
        $court = $this->makeCourt(100000);
        $this->makeRule($court, 1, '18:00', '23:00', 150000);

        $result = $this->service->calculate($court, '2026-01-05', [
            ['start_time' => '17:30', 'end_time' => '18:30'],
        ]);

        $this->assertEquals(1.0, $result['items'][0]['hours']);
        $this->assertEquals(125000, $result['total']);
    }

    public function test_menjumlahkan_beban_beberapa_slot(): void
    {
        $court = $this->makeCourt(50000);

        $result = $this->service->calculate($court, '2026-01-05', [
            ['start_time' => '08:00', 'end_time' => '10:00'],
            ['start_time' => '14:00', 'end_time' => '15:00'],
        ]);

        $this->assertCount(2, $result['items']);
        $this->assertEquals(150000, $result['total']);
    }

    public function test_menolak_rentang_waktu_slot_yang_tidak_valid(): void
    {
        $court = $this->makeCourt(50000);

        $this->expectException(InvalidArgumentException::class);

        $this->service->calculate($court, '2026-01-05', [
            ['start_time' => '10:00', 'end_time' => '08:00'],
        ]);
    }
}
