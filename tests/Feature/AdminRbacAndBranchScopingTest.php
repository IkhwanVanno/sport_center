<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Court;
use App\Models\CourtPriceRule;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRbacAndBranchScopingTest extends TestCase
{
    use RefreshDatabase;

    protected Branch $branch1;
    protected Branch $branch2;
    protected User $superadmin;
    protected User $adminBranch1;
    protected User $adminBranch2;
    protected Sport $futsal;
    protected Court $courtBranch1;
    protected Court $courtBranch2;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Cabang
        $this->branch1 = Branch::create([
            'name' => 'Sport Center Cabang MERR',
            'slug' => 'sport-center-cabang-merr',
            'address' => 'Jl. MERR No. 1, Surabaya',
            'phone' => '08111111111',
            'is_active' => true,
        ]);

        $this->branch2 = Branch::create([
            'name' => 'Sport Center Cabang Barat',
            'slug' => 'sport-center-cabang-barat',
            'address' => 'Jl. Barat No. 2, Surabaya',
            'phone' => '08222222222',
            'is_active' => true,
        ]);

        // 2. Setup Pengguna
        $this->superadmin = User::create([
            'name' => 'Superadmin Global',
            'email' => 'superadmin@example.com',
            'phone_number' => '08999999999',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'branch_id' => null,
        ]);

        $this->adminBranch1 = User::create([
            'name' => 'Admin Cabang MERR',
            'email' => 'admin.merr@example.com',
            'phone_number' => '08777777771',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'branch_id' => $this->branch1->id,
        ]);

        $this->adminBranch2 = User::create([
            'name' => 'Admin Cabang Barat',
            'email' => 'admin.barat@example.com',
            'phone_number' => '08777777772',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'branch_id' => $this->branch2->id,
        ]);

        // 3. Setup Olahraga
        $this->futsal = Sport::create([
            'name' => 'Futsal',
            'slug' => 'futsal',
            'icon' => 'bi-futbol',
        ]);

        // 4. Setup Lapangan
        $this->courtBranch1 = Court::create([
            'branch_id' => $this->branch1->id,
            'sport_id' => $this->futsal->id,
            'name' => 'Lapangan Futsal MERR A',
            'slug' => 'futsal-merr-a',
            'base_price_per_hour' => 100000,
            'is_active' => true,
        ]);

        $this->courtBranch2 = Court::create([
            'branch_id' => $this->branch2->id,
            'sport_id' => $this->futsal->id,
            'name' => 'Lapangan Futsal Barat B',
            'slug' => 'futsal-barat-b',
            'base_price_per_hour' => 120000,
            'is_active' => true,
        ]);
    }

    public function test_superadmin_can_access_all_admin_menus_including_users_branches_and_sports(): void
    {
        $this->actingAs($this->superadmin);

        $this->get(route('admin.dashboard'))->assertOk();
        $this->get(route('admin.users.index'))->assertOk();
        $this->get(route('admin.branches.index'))->assertOk();
        $this->get(route('admin.sports.index'))->assertOk();
        $this->get(route('admin.courts.index'))->assertOk();
        $this->get(route('admin.price-rules.index'))->assertOk();
        $this->get(route('admin.orders.index'))->assertOk();
        $this->get(route('admin.reports.revenue'))->assertOk();
        $this->get(route('admin.facilities.index'))->assertOk();
    }

    public function test_branch_admin_cannot_access_users_branches_or_sports(): void
    {
        $this->actingAs($this->adminBranch1);

        // Menu yang dilarang harus menghasilkan 403 Forbidden
        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.branches.index'))->assertForbidden();
        $this->get(route('admin.sports.index'))->assertForbidden();
    }

    public function test_branch_admin_with_inactive_branch_is_blocked_by_middleware(): void
    {
        $inactiveBranch = Branch::create([
            'name' => 'Cabang Non-Aktif',
            'slug' => 'cabang-non-aktif',
            'address' => 'Jl. Tutup',
            'phone' => '08999999990',
            'is_active' => false,
        ]);

        $adminInactive = User::create([
            'name' => 'Admin Cabang Non-Aktif',
            'email' => 'admin.inactive@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'branch_id' => $inactiveBranch->id,
        ]);

        $this->actingAs($adminInactive);

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('admin.courts.index'))->assertForbidden();
    }

    public function test_branch_admin_without_branch_cannot_create_court(): void
    {
        $adminTanpaCabang = User::create([
            'name' => 'Admin Tanpa Cabang',
            'email' => 'admin.tanpacabang@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'branch_id' => null,
        ]);

        $this->actingAs($adminTanpaCabang);

        $this->get(route('admin.courts.create'))->assertForbidden();
    }

    public function test_branch_admin_only_sees_courts_from_their_own_branch(): void
    {
        $this->actingAs($this->adminBranch1);

        $response = $this->get(route('admin.courts.index'));
        $response->assertOk();
        $response->assertSee('Lapangan Futsal MERR A');
        $response->assertDontSee('Lapangan Futsal Barat B');
    }

    public function test_branch_admin_cannot_update_court_from_another_branch(): void
    {
        $this->actingAs($this->adminBranch1);

        // Percobaan edit lapangan milik branch2 harus 403 Forbidden (Anti-IDOR)
        $this->get(route('admin.courts.edit', $this->courtBranch2))->assertForbidden();

        $response = $this->put(route('admin.courts.update', $this->courtBranch2), [
            'name' => 'Hacked Court Name',
            'sport_id' => $this->futsal->id,
            'base_price_per_hour' => 99999,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('courts', ['name' => 'Hacked Court Name']);
    }

    public function test_branch_admin_cannot_delete_court_from_another_branch(): void
    {
        $this->actingAs($this->adminBranch1);

        $response = $this->delete(route('admin.courts.destroy', $this->courtBranch2));
        $response->assertForbidden();

        $this->assertDatabaseHas('courts', ['id' => $this->courtBranch2->id]);
    }

    public function test_court_created_by_branch_admin_is_automatically_bound_to_their_branch(): void
    {
        $this->actingAs($this->adminBranch1);

        $response = $this->post(route('admin.courts.store'), [
            'name' => 'Lapangan Badminton MERR Baru',
            'sport_id' => $this->futsal->id,
            'base_price_per_hour' => 85000,
            'branch_id' => $this->branch2->id, // Mencoba memanipulasi form ke branch2
        ]);

        $response->assertRedirect(route('admin.courts.index'));

        // Harus tetap tersimpan dengan branch_id = branch1 milik admin yang login
        $this->assertDatabaseHas('courts', [
            'name' => 'Lapangan Badminton MERR Baru',
            'branch_id' => $this->branch1->id,
        ]);
    }

    public function test_branch_admin_only_sees_price_rules_for_courts_in_their_branch(): void
    {
        $ruleMERR = CourtPriceRule::create([
            'court_id' => $this->courtBranch1->id,
            'day_of_week' => 1,
            'start_time' => '18:00',
            'end_time' => '22:00',
            'price_per_hour' => 150000,
        ]);

        $ruleBarat = CourtPriceRule::create([
            'court_id' => $this->courtBranch2->id,
            'day_of_week' => 1,
            'start_time' => '18:00',
            'end_time' => '22:00',
            'price_per_hour' => 170000,
        ]);

        $this->actingAs($this->adminBranch1);

        $response = $this->get(route('admin.price-rules.index'));
        $response->assertOk();
        $response->assertSee('Lapangan Futsal MERR A');
        $response->assertDontSee('Lapangan Futsal Barat B');
    }

    public function test_branch_admin_cannot_delete_price_rule_from_another_branch(): void
    {
        $ruleBarat = CourtPriceRule::create([
            'court_id' => $this->courtBranch2->id,
            'day_of_week' => 1,
            'start_time' => '18:00',
            'end_time' => '22:00',
            'price_per_hour' => 170000,
        ]);

        $this->actingAs($this->adminBranch1);

        $response = $this->delete(route('admin.price-rules.destroy', $ruleBarat));
        $response->assertForbidden();

        $this->assertDatabaseHas('court_price_rules', ['id' => $ruleBarat->id]);
    }

    public function test_branch_admin_cannot_create_price_rule_for_court_of_another_branch(): void
    {
        $this->actingAs($this->adminBranch1);

        $response = $this->post(route('admin.price-rules.store'), [
            'court_id' => $this->courtBranch2->id,
            'day_of_week' => 2,
            'start_time' => '17:00',
            'end_time' => '21:00',
            'price_per_hour' => 200000,
        ]);

        $response->assertSessionHasErrors('court_id');
        $this->assertDatabaseMissing('court_price_rules', [
            'court_id' => $this->courtBranch2->id,
            'day_of_week' => 2,
        ]);
    }

    public function test_branch_admin_only_sees_orders_from_their_own_branch(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Uji',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $bookingMERR = Booking::create([
            'booking_code' => 'BK-MERR-001',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch1->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 100000,
            'status' => 'pending',
            'payment_status' => 'pending_verification',
            'expired_at' => now()->addHours(2),
        ]);

        $bookingBarat = Booking::create([
            'booking_code' => 'BK-BARAT-002',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch2->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 120000,
            'status' => 'pending',
            'payment_status' => 'pending_verification',
            'expired_at' => now()->addHours(2),
        ]);

        $this->actingAs($this->adminBranch1);

        $response = $this->get(route('admin.orders.index'));
        $response->assertOk();
        $response->assertSee('BK-MERR-001');
        $response->assertDontSee('BK-BARAT-002');
    }

    public function test_branch_admin_cannot_view_or_confirm_order_from_another_branch(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Uji 2',
            'email' => 'pelanggan2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $bookingBarat = Booking::create([
            'booking_code' => 'BK-BARAT-003',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch2->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 120000,
            'status' => 'pending',
            'payment_status' => 'pending_verification',
            'expired_at' => now()->addHours(2),
        ]);

        $this->actingAs($this->adminBranch1);

        // Cek IDOR show
        $this->get(route('admin.orders.show', $bookingBarat))->assertForbidden();

        // Cek IDOR confirm payment
        $response = $this->post(route('admin.orders.confirm-payment', $bookingBarat));
        $response->assertForbidden();

        $this->assertEquals('pending', $bookingBarat->fresh()->status);
        $this->assertEquals('pending_verification', $bookingBarat->fresh()->payment_status);
    }

    public function test_branch_admin_can_confirm_order_payment_for_their_branch(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Uji 3',
            'email' => 'pelanggan3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $bookingMERR = Booking::create([
            'booking_code' => 'BK-MERR-004',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch1->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 100000,
            'status' => 'pending',
            'payment_status' => 'pending_verification',
            'expired_at' => now()->addHours(2),
        ]);

        Payment::create([
            'booking_id' => $bookingMERR->id,
            'payment_method' => 'transfer',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminBranch1);

        $response = $this->post(route('admin.orders.confirm-payment', $bookingMERR));
        $response->assertRedirect();

        $this->assertEquals('confirmed', $bookingMERR->fresh()->status);
        $this->assertEquals('paid', $bookingMERR->fresh()->payment_status);
    }

    public function test_branch_admin_cannot_mutate_master_facilities(): void
    {
        $facility = Facility::create([
            'name' => 'Shower Mewah',
            'icon' => 'bi-droplet',
        ]);

        $this->actingAs($this->adminBranch1);

        // Boleh melihat daftar
        $this->get(route('admin.facilities.index'))->assertOk();

        // Dilarang menambah
        $this->get(route('admin.facilities.create'))->assertForbidden();
        $this->post(route('admin.facilities.store'), ['name' => 'AC Sentral'])->assertForbidden();

        // Dilarang mengedit atau menghapus
        $this->get(route('admin.facilities.edit', $facility))->assertForbidden();
        $this->put(route('admin.facilities.update', $facility), ['name' => 'Shower Biasa'])->assertForbidden();
        $this->delete(route('admin.facilities.destroy', $facility))->assertForbidden();
    }

    public function test_superadmin_can_crud_users_and_reset_password(): void
    {
        $this->actingAs($this->superadmin);

        // Buat Operator Baru
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Operator Baru MERR',
            'email' => 'operator.baru@example.com',
            'phone_number' => '08555555555',
            'role' => 'operator',
            'branch_id' => $this->branch1->id,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $newUser = User::where('email', 'operator.baru@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('operator', $newUser->role);
        $this->assertEquals($this->branch1->id, $newUser->branch_id);

        // Edit Pengguna
        $this->put(route('admin.users.update', $newUser), [
            'name' => 'Operator Diperbarui',
            'email' => 'operator.baru@example.com',
            'role' => 'operator',
            'branch_id' => $this->branch1->id,
        ])->assertRedirect(route('admin.users.index'));

        $this->assertEquals('Operator Diperbarui', $newUser->fresh()->name);

        // Reset Password
        $this->post(route('admin.users.reset-password', $newUser), [
            'password' => 'NewSecretPassword123',
            'password_confirmation' => 'NewSecretPassword123',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('NewSecretPassword123', $newUser->fresh()->password));

        // Hapus Pengguna
        $this->delete(route('admin.users.destroy', $newUser))->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_superadmin_cannot_delete_self(): void
    {
        $this->actingAs($this->superadmin);

        $response = $this->delete(route('admin.users.destroy', $this->superadmin));
        $response->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $this->superadmin->id]);
    }

    public function test_revenue_report_is_automatically_locked_to_branch_for_branch_admin(): void
    {
        $customer = User::create([
            'name' => 'Customer Transaksi',
            'email' => 'transaksi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // Transaksi lunas di MERR (100.000)
        Booking::create([
            'booking_code' => 'BK-REV-MERR',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch1->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 100000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->addHours(2),
        ]);

        // Transaksi lunas di Barat (250.000)
        Booking::create([
            'booking_code' => 'BK-REV-BARAT',
            'user_id' => $customer->id,
            'court_id' => $this->courtBranch2->id,
            'booking_date' => today()->toDateString(),
            'total_amount' => 250000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'expired_at' => now()->addHours(2),
        ]);

        // Login sebagai Admin Cabang MERR
        $this->actingAs($this->adminBranch1);

        // Walau mencoba mengirimkan parameter branch_id cabang barat, service layer tetap lock ke MERR
        $response = $this->get(route('admin.reports.revenue', ['branch_id' => $this->branch2->id]));
        $response->assertOk();
        $response->assertSee('BK-REV-MERR');
        $response->assertDontSee('BK-REV-BARAT');
        $response->assertViewHas('totalRevenue', 100000.0);
    }
}
