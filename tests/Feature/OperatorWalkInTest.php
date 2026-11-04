<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class OperatorWalkInTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    private function makeOperator(int $branchId): User
    {
        return User::create([
            'name' => 'Operator Uji',
            'email' => 'operator-'.uniqid().'@example.com',
            'phone_number' => '081200000000',
            'password' => 'Password123!',
            'role' => 'operator',
            'branch_id' => $branchId,
        ]);
    }

    public function test_walk_in_berhasil_membuat_booking_lunas_tunai(): void
    {
        $court = $this->makeCourt(100000);
        $operator = $this->makeOperator($court->branch_id);

        $response = $this->actingAs($operator)->post(route('operator.walkin.store'), [
            'court_id' => $court->id,
            'date' => now()->addDay()->toDateString(),
            'slots' => ['08:00'],
            'customer_name' => 'Pelanggan Walk In',
            'customer_phone' => '081298765432',
        ]);

        $booking = Booking::firstOrFail();

        $response->assertRedirect(route('operator.checkin.index', ['code' => $booking->booking_code]));
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame('paid', $booking->payment_status);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'payment_method' => 'cash',
            'status' => 'success',
        ]);
        $this->assertDatabaseHas('users', [
            'phone_number' => '081298765432',
            'role' => 'customer',
        ]);
    }

    public function test_walk_in_yang_bentrok_tidak_meninggalkan_akun_pelanggan(): void
    {
        $court = $this->makeCourt(100000);
        $operator = $this->makeOperator($court->branch_id);
        $date = now()->addDay()->toDateString();

        $this->makeBooking($court, ['booking_date' => $date], [
            ['start_time' => '08:00', 'end_time' => '09:00'],
        ]);

        $usersBefore = User::count();
        $bookingsBefore = Booking::count();

        $response = $this->actingAs($operator)->post(route('operator.walkin.store'), [
            'court_id' => $court->id,
            'date' => $date,
            'slots' => ['08:00'],
            'customer_name' => 'Bentrok Uji',
            'customer_phone' => '089911112222',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertSame($usersBefore, User::count(), 'Akun walk-in tidak boleh tersisa saat booking gagal.');
        $this->assertSame($bookingsBefore, Booking::count());
        $this->assertDatabaseMissing('users', ['phone_number' => '089911112222']);
    }

    public function test_verifikasi_check_in_mewajibkan_kode_booking(): void
    {
        $court = $this->makeCourt();
        $operator = $this->makeOperator($court->branch_id);

        $response = $this->actingAs($operator)->post(route('operator.checkin.verify'), [
            'booking_code' => '',
        ]);

        $response->assertSessionHasErrors('booking_code');
    }
}
