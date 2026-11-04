<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    private BookingService $service;

    /** Tanggal masa depan yang dipakai di semua test (besok). */
    private string $futureDate;

    /** Hari dalam seminggu (1=Senin..7=Minggu) dari $futureDate. */
    private int $futureDayOfWeek;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service         = app(BookingService::class);
        $tomorrow              = Carbon::tomorrow();
        $this->futureDate      = $tomorrow->format('Y-m-d');
        $this->futureDayOfWeek = (int) $tomorrow->format('N'); // ISO: 1=Mon, 7=Sun
    }

    public function test_booking_baru_menjadi_pending_dengan_kode_dan_item(): void
    {
        $court = $this->makeCourt(120000);
        $user  = $this->makeCustomer();

        $booking = $this->service->book($user, $court, $this->futureDate, ['09:00', '08:00']);

        $this->assertSame('pending', $booking->status);
        $this->assertSame('unpaid', $booking->payment_status);
        $this->assertCount(2, $booking->items);
        $this->assertEquals(240000, $booking->total_amount);
        $this->assertSame('08:00', $booking->items->first()->start_time);
        $this->assertMatchesRegularExpression('/^BK-\d{6}-[A-Z0-9]{6}$/', $booking->booking_code);
        $this->assertTrue($booking->expired_at->isFuture());
    }

    public function test_menolak_slot_yang_sudah_terpakai(): void
    {
        $court = $this->makeCourt();
        $this->makeBooking($court, ['status' => 'confirmed'], [
            ['start_time' => '10:00', 'end_time' => '11:00'],
        ]);

        try {
            $this->service->book($this->makeCustomer(), $court, $this->futureDate, ['10:00']);
            $this->fail('Booking seharusnya ditolak karena slot sudah terpakai.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('slots', $exception->errors());
            $this->assertStringContainsString('sudah dipesan', $exception->errors()['slots'][0]);
        }

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_menolak_jam_di_luar_jam_operasional(): void
    {
        $court = $this->makeCourt();

        foreach (['07:00', '23:00'] as $jam) {
            try {
                $this->service->book($this->makeCustomer(), $court, $this->futureDate, [$jam]);
                $this->fail('Slot pukul '.$jam.' seharusnya ditolak.');
            } catch (ValidationException $exception) {
                $this->assertStringContainsString('jam operasional', $exception->errors()['slots'][0]);
            }
        }

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_menolak_slot_yang_tidak_berbentuk_satu_jam_penuh(): void
    {
        $court = $this->makeCourt();

        try {
            $this->service->book($this->makeCustomer(), $court, $this->futureDate, ['08:30']);
            $this->fail('Slot setengah jam seharusnya ditolak.');
        } catch (ValidationException $exception) {
            $this->assertStringContainsString('satu jam penuh', $exception->errors()['slots'][0]);
        }

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_menyimpan_snapshot_harga_puncak_pada_item(): void
    {
        $court = $this->makeCourt(120000);
        // Buat rule untuk hari yang sesuai dengan futureDate
        $this->makeRule($court, $this->futureDayOfWeek, '18:00', '23:00', 150000);

        $booking = $this->service->book($this->makeCustomer(), $court, $this->futureDate, ['17:00', '18:00']);

        $this->assertEquals(270000, $booking->total_amount);
        $this->assertEquals(120000, $booking->items->first()->price);
        $this->assertEquals(150000, $booking->items->last()->price);
    }

    public function test_slot_duplikat_dihitung_hanya_sekali(): void
    {
        $court = $this->makeCourt(120000);

        $booking = $this->service->book($this->makeCustomer(), $court, $this->futureDate, ['08:00', '08:00']);

        $this->assertCount(1, $booking->items);
        $this->assertEquals(120000, $booking->total_amount);
    }

    public function test_menolak_tanggal_yang_sudah_lampau(): void
    {
        $court = $this->makeCourt();

        $this->expectException(ValidationException::class);

        $this->service->book($this->makeCustomer(), $court, '2020-01-01', ['09:00']);
    }
}
