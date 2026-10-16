<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Arahkan user ke dashboard sesuai role-nya.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route(match ($request->user()->role) {
            'superadmin', 'admin' => 'admin.dashboard',
            'operator' => 'operator.dashboard',
            default => 'customer.dashboard',
        });
    }

    // Dashboard superadmin & admin (statistik dan manajemen).
    public function admin(): View
    {
        return view('admin.dashboard');
    }

    // Dashboard operator cabang (verifikasi pembayaran dan check-in).
    public function operator(): View
    {
        return view('operator.dashboard');
    }

    // Dashboard pelanggan (daftar booking miliknya).
    public function customer(): View
    {
        return view('customer.dashboard');
    }
}
