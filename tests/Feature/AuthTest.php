<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat user uji dengan hash password yang valid.
     */
    protected function makeUser(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '081234567890',
            'password' => Hash::make('Password123!'),
            'role' => 'customer',
        ], $attributes));
    }

    public function test_customer_can_authenticate_and_redirects_to_user_dashboard(): void
    {
        $customer = $this->makeUser(['role' => 'customer']);

        $response = $this->post('/login', [
            'email' => $customer->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($customer);
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_operator_can_authenticate_and_redirects_to_operator_checkin(): void
    {
        $operator = $this->makeUser(['email' => 'operator@example.com', 'role' => 'operator']);

        $response = $this->post('/login', [
            'email' => $operator->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($operator);
        $response->assertRedirect(route('operator.checkin.index'));
    }

    public function test_admin_can_authenticate_and_redirects_to_admin_dashboard(): void
    {
        $admin = $this->makeUser(['email' => 'admin@example.com', 'role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_superadmin_can_authenticate_and_redirects_to_admin_dashboard(): void
    {
        $superadmin = $this->makeUser(['email' => 'superadmin@example.com', 'role' => 'superadmin']);

        $response = $this->post('/login', [
            'email' => $superadmin->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($superadmin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = $this->makeUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_new_users_can_register_as_customer(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Baru',
            'email' => 'budi.baru@example.com',
            'phone_number' => '081298765432',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'budi.baru@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_registration_requires_accepted_terms(): void
    {
        $this->post('/register', [
            'name' => 'Tanpa Terms',
            'email' => 'noterms@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertSessionHasErrors('terms');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/user/dashboard')->assertRedirect(route('login'));
        $this->get('/operator/checkin')->assertRedirect(route('login'));
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_dashboard_redirects_to_role_dashboard(): void
    {
        $admin = $this->makeUser(['email' => 'admin@example.com', 'role' => 'admin']);
        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));

        $operator = $this->makeUser(['email' => 'operator@example.com', 'role' => 'operator']);
        $this->actingAs($operator)
            ->get('/dashboard')
            ->assertRedirect(route('operator.checkin.index'));

        $customer = $this->makeUser(['email' => 'customer@example.com', 'role' => 'customer']);
        $this->actingAs($customer)
            ->get('/dashboard')
            ->assertRedirect(route('user.dashboard'));
    }

    public function test_role_middleware_handles_pipe_separated_multi_roles(): void
    {
        // Rute Operator & Superadmin
        $operator = $this->makeUser(['email' => 'operator@example.com', 'role' => 'operator']);
        $superadmin = $this->makeUser(['email' => 'superadmin@example.com', 'role' => 'superadmin']);
        $admin = $this->makeUser(['email' => 'admin@example.com', 'role' => 'admin']);
        $customer = $this->makeUser(['email' => 'customer@example.com', 'role' => 'customer']);

        // Operator dan Superadmin boleh akses /operator/checkin
        $this->actingAs($operator)->get('/operator/checkin')->assertOk();
        $this->actingAs($superadmin)->get('/operator/checkin')->assertOk();
        // Admin dan Customer ditolak akses ke /operator/checkin
        $this->actingAs($admin)->get('/operator/checkin')->assertForbidden();
        $this->actingAs($customer)->get('/operator/checkin')->assertForbidden();

        // Admin dan Superadmin boleh akses /admin/dashboard
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->actingAs($superadmin)->get('/admin/dashboard')->assertOk();
        // Operator dan Customer ditolak akses ke /admin/dashboard
        $this->actingAs($operator)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($customer)->get('/admin/dashboard')->assertForbidden();

        // Customer boleh akses /user/dashboard
        $this->actingAs($customer)->get('/user/dashboard')->assertOk();
        // Operator dan Admin ditolak akses ke /user/dashboard
        $this->actingAs($operator)->get('/user/dashboard')->assertForbidden();
        $this->actingAs($admin)->get('/user/dashboard')->assertForbidden();
    }

    public function test_users_can_logout(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
