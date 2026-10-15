<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
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

    public function test_users_can_authenticate(): void
    {
        $user = $this->makeUser();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
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
        $response->assertRedirect(route('dashboard'));

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
    }

    public function test_dashboard_redirects_to_role_dashboard(): void
    {
        $admin = $this->makeUser(['email' => 'admin@example.com', 'role' => 'admin']);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_role_middleware_allows_matching_role(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_test/area-admin', fn () => 'ok')
            ->name('test.area-admin');

        $admin = $this->makeUser(['email' => 'admin@example.com', 'role' => 'admin']);

        $this->actingAs($admin)->get('/_test/area-admin')->assertOk();
    }

    public function test_role_middleware_blocks_unauthorized_role(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_test/area-admin', fn () => 'ok')
            ->name('test.area-admin');

        $customer = $this->makeUser(['email' => 'customer@example.com', 'role' => 'customer']);

        $this->actingAs($customer)->get('/_test/area-admin')->assertForbidden();
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
