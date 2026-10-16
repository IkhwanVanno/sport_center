<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login. Session di-regenerate untuk mencegah session fixation.
     * Redirect berdasarkan role pengguna:
     * - customer   => user.dashboard (/user/dashboard)
     * - operator   => operator.checkin.index (/operator/checkin)
     * - admin/superadmin => admin.dashboard (/admin/dashboard)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $targetRoute = match ($user?->role) {
            'superadmin', 'admin' => 'admin.dashboard',
            'operator' => 'operator.checkin.index',
            default => 'user.dashboard',
        };

        return redirect()->intended(route($targetRoute));
    }

    /**
     * Logout lalu bersihkan session dan token CSRF.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
