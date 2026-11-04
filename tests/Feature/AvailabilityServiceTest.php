<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    private AvailabilityService $service;

    /** Tanggal Senin terdekat di masa depan, dipakai di semua test. */
    private string $testDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service  = app(AvailabilityService::class);
        // Ambil Senin depan (atau besok jika hari ini bukan Senin)
        // supaya day_of_week = 1 (Senin) selalu cocok dengan tanggal test.
        $this->testDate = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');
    }

    private function statusPerSlot($court): array
    {
        return collect($this->service->getSlots($court, $this->testDate))
            ->pluck('status', 'start_time')
            ->all();
    }

    public function test_menyediakan_satu_slot_setiap_jam_dari_buka_sampai_tutup(): void
    {
        $court = $this->makeCourt();

        $slots = $this->service->getSlots($court, $this->testDate);

        $this->assertCount(15, $slots);
        $this->assertSame('08:00', $slots[0]['start_time']);
        $this->assertSame('23:00', $slots[14]['end_time']);
        $this->assertSame('available', $slots[0]['status']);
    }

    public function test_booking_aktif_mengunci_jam_pada_rentang_item(): void
    {
        $court = $this->makeCourt();
        $this->makeBooking($court, ['status' => 'confirmed', 'booking_date' => $this->testDate], [
            ['start_time' => '10:00', 'end_time' => '12:00', 'price' => 240000],
        ]);

        $status = $this->statusPerSlot($court);

        $this->assertSame('available', $status['09:00']);
        $this->assertSame('booked', $status['10:00']);
        $this->assertSame('booked', $status['11:00']);
        $this->assertSame('available', $status['12:00']);
    }

    public function test_booking_pending_mengunci_selama_masih_dalam_batas_waktu(): void
    {
        $court = $this->makeCourt();
        $this->makeBooking($court, ['status' => 'pending', 'expired_at' => now()->addMinutes(10), 'booking_date' => $this->testDate], [
            ['start_time' => '14:00', 'end_time' => '15:00'],
        ]);
        $this->makeBooking($court, ['status' => 'pending', 'expired_at' => now()->subMinutes(5), 'booking_date' => $this->testDate], [
            ['start_time' => '16:00', 'end_time' => '17:00'],
        ]);

        $status = $this->statusPerSlot($court);

        $this->assertSame('pending', $status['14:00']);
        $this->assertSame('available', $status['16:00']);
    }

    public function test_booking_dibatalkan_atau_kedaluwarsa_tidak_mengunci_slot(): void
    {
        $court = $this->makeCourt();
        $this->makeBooking($court, ['status' => 'cancelled', 'booking_date' => $this->testDate], [
            ['start_time' => '10:00', 'end_time' => '12:00'],
        ]);
        $this->makeBooking($court, ['status' => 'expired', 'booking_date' => $this->testDate], [
            ['start_time' => '13:00', 'end_time' => '14:00'],
        ]);

        $status = $this->statusPerSlot($court);

        $this->assertSame('available', $status['10:00']);
        $this->assertSame('available', $status['11:00']);
        $this->assertSame('available', $status['13:00']);
    }

    public function test_status_terkonfirmasi_menang_daripada_status_pending(): void
    {
        $court = $this->makeCourt();
        $this->makeBooking($court, ['status' => 'pending', 'expired_at' => now()->addMinutes(10), 'booking_date' => $this->testDate], [
            ['start_time' => '18:00', 'end_time' => '20:00'],
        ]);
        $this->makeBooking($court, ['status' => 'in_use', 'booking_date' => $this->testDate], [
            ['start_time' => '19:00', 'end_time' => '21:00'],
        ]);

        $status = $this->statusPerSlot($court);

        $this->assertSame('pending', $status['18:00']);
        $this->assertSame('booked', $status['19:00']);
        $this->assertSame('booked', $status['20:00']);
    }

    public function test_harga_per_slot_mengikuti_tarif_hari_tersebut(): void
    {
        $court = $this->makeCourt(120000);
        // day_of_week = 1 = Senin, cocok dengan $this->testDate
        $this->makeRule($court, 1, '18:00', '23:00', 150000);

        $prices = collect($this->service->getSlots($court, $this->testDate))
            ->pluck('price_per_hour', 'start_time');

        $this->assertEquals(120000, $prices['17:00']);
        $this->assertEquals(150000, $prices['18:00']);
        $this->assertEquals(150000, $prices['22:00']);
    }
}
