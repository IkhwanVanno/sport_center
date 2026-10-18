<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\ExpirePendingBookingJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class ExpirePendingBookingJobTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_job_membatalkan_booking_pending_yang_sudah_lewat_batas(): void
    {
        $booking = $this->makeBooking($this->makeCourt(), [
            'status' => 'pending',
            'expired_at' => now()->subMinute(),
        ]);

        (new ExpirePendingBookingJob($booking->id))->handle();

        $this->assertSame('expired', $booking->fresh()->status);
    }

    public function test_job_tidak_menyentuh_booking_yang_masih_dalam_batas_waktu(): void
    {
        $booking = $this->makeBooking($this->makeCourt(), [
            'status' => 'pending',
            'expired_at' => now()->addMinutes(10),
        ]);

        (new ExpirePendingBookingJob($booking->id))->handle();

        $this->assertSame('pending', $booking->fresh()->status);
    }

    public function test_job_tidak_menyentuh_booking_yang_sudah_dikonfirmasi(): void
    {
        $booking = $this->makeBooking($this->makeCourt(), [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->subMinutes(30),
        ]);

        (new ExpirePendingBookingJob($booking->id))->handle();

        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_perintah_sweep_membatalkan_hanya_booking_pending_kedaluwarsa(): void
    {
        $kedaluwarsa = $this->makeBooking($this->makeCourt(), [
            'status' => 'pending',
            'expired_at' => now()->subMinute(),
        ]);
        $masih_berlaku = $this->makeBooking($this->makeCourt(), [
            'status' => 'pending',
            'expired_at' => now()->addMinutes(10),
        ]);
        $sudah_dibayar = $this->makeBooking($this->makeCourt(), [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->subMinutes(30),
        ]);

        $this->artisan('bookings:expire')->assertSuccessful();

        $this->assertSame('expired', $kedaluwarsa->fresh()->status);
        $this->assertSame('pending', $masih_berlaku->fresh()->status);
        $this->assertSame('confirmed', $sudah_dibayar->fresh()->status);
    }
}
