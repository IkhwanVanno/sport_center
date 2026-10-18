<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_pelanggan_dapat_membuat_booking_lewat_form(): void
    {
        $court = $this->makeCourt(120000);
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->post(route('user.bookings.store'), [
            'court_id' => $court->id,
            'date' => now()->addDay()->toDateString(),
            'slots' => ['08:00', '09:00'],
        ]);

        $booking = Booking::firstOrFail();

        $response->assertRedirect(route('user.bookings.show', $booking->booking_code));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'court_id' => $court->id,
            'status' => 'pending',
            'total_amount' => 240000,
        ]);
    }

    public function test_form_booking_wajib_mengisi_slot(): void
    {
        $court = $this->makeCourt();
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->post(route('user.bookings.store'), [
                'court_id' => $court->id,
                'date' => now()->addDay()->toDateString(),
            ])
            ->assertSessionHasErrors('slots');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_lapangan_yang_tidak_aktif_tidak_bisa_dipesan(): void
    {
        $court = $this->makeCourt();
        $court->update(['is_active' => false]);
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->post(route('user.bookings.store'), [
                'court_id' => $court->id,
                'date' => now()->addDay()->toDateString(),
                'slots' => ['08:00'],
            ])
            ->assertSessionHasErrors('court_id');

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_pelanggan_lain_tidak_bisa_membuka_detail_booking_orang_lain(): void
    {
        $court = $this->makeCourt();
        $booking = $this->makeBooking($court);
        $pelangganLain = $this->makeCustomer();

        $this->actingAs($pelangganLain)
            ->get(route('user.bookings.show', $booking->booking_code))
            ->assertForbidden();
    }

    public function test_operator_tidak_dapat_memesan_melalui_form_pelanggan(): void
    {
        $court = $this->makeCourt();
        $operator = $this->makeCustomer(['role' => 'operator']);

        $this->actingAs($operator)
            ->post(route('user.bookings.store'), [
                'court_id' => $court->id,
                'date' => now()->addDay()->toDateString(),
                'slots' => ['08:00'],
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_halaman_detail_booking_mengambil_data_booking(): void
    {
        $court = $this->makeCourt();
        $booking = $this->makeBooking($court);

        $response = $this->actingAs($booking->user)->get(route('user.bookings.show', $booking->booking_code));

        $response->assertOk();
        $this->assertTrue($response->viewData('booking')->is($booking));
    }
}
