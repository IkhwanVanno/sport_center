<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class PaymentProofTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_bukti_transfer_dapat_diunggah_untuk_booking_pending(): void
    {
        Storage::fake('public');

        $court = $this->makeCourt();
        $customer = $this->makeCustomer();
        $booking = $this->makeBooking($court, [
            'user_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $response = $this->actingAs($customer)->post(
            route('user.payments.upload-proof', $booking->booking_code),
            [
                'proof_of_payment' => UploadedFile::fake()->image('bukti.png'),
                'bank_sender' => 'BCA',
            ],
        );

        $response->assertRedirect(route('user.bookings.show', $booking->booking_code));
        $this->assertSame('pending_verification', $booking->fresh()->payment_status);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'status' => 'pending',
        ]);
    }

    public function test_bukti_transfer_ditolak_untuk_booking_yang_sudah_lunas(): void
    {
        Storage::fake('public');

        $court = $this->makeCourt();
        $customer = $this->makeCustomer();
        $booking = $this->makeBooking($court, [
            'user_id' => $customer->id,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        $response = $this->actingAs($customer)->post(
            route('user.payments.upload-proof', $booking->booking_code),
            ['proof_of_payment' => UploadedFile::fake()->image('bukti.png')],
        );

        $response->assertRedirect(route('user.bookings.show', $booking->booking_code));
        $response->assertSessionHas('error');
        $this->assertSame('paid', $booking->fresh()->payment_status);
        $this->assertDatabaseMissing('payments', ['booking_id' => $booking->id]);
    }
}
