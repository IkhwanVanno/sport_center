<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesCourts;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    use CreatesCourts;
    use RefreshDatabase;

    public function test_public_pages_render_successfully(): void
    {
        $court = $this->makeCourt();

        $this->get('/')->assertOk()->assertSee('Main Olahraga Tanpa Ribet');
        $this->get('/courts')->assertOk()->assertSee($court->name);
        $this->get('/courts/'.$court->slug)->assertOk()->assertSee($court->name);
    }

    public function test_customer_pages_render_successfully(): void
    {
        $court = $this->makeCourt();
        $booking = $this->makeBooking($court);
        $customer = $booking->user;

        $this->actingAs($customer)->get(route('user.dashboard'))->assertOk()->assertSee('Dashboard Pelanggan');
        $this->actingAs($customer)->get(route('user.profile'))->assertOk()->assertSee('Profil Saya');
        $this->actingAs($customer)->get(route('user.bookings.index'))->assertOk()->assertSee($booking->booking_code);
        $this->actingAs($customer)->get(route('user.bookings.show', $booking->booking_code))->assertOk()->assertSee($booking->booking_code);
        $this->actingAs($customer)->get(route('user.payments.checkout', $booking->booking_code))->assertOk()->assertSee('Ringkasan Tagihan');
        $this->actingAs($customer)->get(route('user.payments.upload-proof-form', $booking->booking_code))->assertOk()->assertSee('Unggah Bukti Transfer');
    }

    public function test_operator_pages_render_successfully(): void
    {
        $operator = $this->makeCustomer(['role' => 'operator']);
        $court = $this->makeCourt();

        $this->actingAs($operator)->get(route('operator.checkin.index'))->assertOk()->assertSee('Check-In');
        $this->actingAs($operator)->get(route('operator.walkin.index'))->assertOk()->assertSee('Pemesanan Walk-In');
        $this->actingAs($operator)->get(route('operator.schedule.index'))->assertOk()->assertSee('Jadwal Penggunaan Lapangan');
    }

    public function test_admin_pages_render_successfully(): void
    {
        $admin = $this->makeCustomer(['role' => 'admin']);
        $court = $this->makeCourt();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Ringkasan Statistik');
        $this->actingAs($admin)->get(route('admin.branches.index'))->assertOk()->assertSee('Daftar Cabang Fasilitas');
        $this->actingAs($admin)->get(route('admin.branches.create'))->assertOk()->assertSee('Tambah Cabang Baru');
        $this->actingAs($admin)->get(route('admin.courts.index'))->assertOk()->assertSee('Daftar Lapangan Olahraga');
        $this->actingAs($admin)->get(route('admin.courts.create'))->assertOk()->assertSee('Tambah Lapangan Baru');
        $this->actingAs($admin)->get(route('admin.price-rules.index'))->assertOk()->assertSee('Aturan Tarif Dinamis');
        $this->actingAs($admin)->get(route('admin.price-rules.create'))->assertOk()->assertSee('Tambah Aturan Tarif Khusus');
        $this->actingAs($admin)->get(route('admin.orders.index'))->assertOk()->assertSee('Daftar Transaksi Pesanan');
        $this->actingAs($admin)->get(route('admin.reports.revenue'))->assertOk()->assertSee('Laporan Omzet Pendapatan');
    }
}
