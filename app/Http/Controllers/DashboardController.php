<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Arahkan user ke dashboard sesuai role-nya.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route(match ($request->user()?->role) {
            'superadmin', 'admin' => 'admin.dashboard',
            'operator' => 'operator.checkin.index',
            default => 'user.dashboard',
        });
    }
}
