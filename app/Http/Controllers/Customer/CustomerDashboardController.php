<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard pelanggan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $bookings = $user->bookings()
            ->with(['court.branch', 'court.sport', 'items'])
            ->latest()
            ->take(5)
            ->get();

        $totalBookings = $user->bookings()->count();
        
        $upcomingBookings = $user->bookings()
            ->where('booking_date', '>=', today()->toDateString())
            ->whereIn('status', ['confirmed', 'in_use'])
            ->count();

        $pendingPayment = $user->bookings()
            ->where('payment_status', 'unpaid')
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->count();

        return view('customer.dashboard', compact(
            'bookings',
            'totalBookings',
            'upcomingBookings',
            'pendingPayment'
        ));
    }

    /**
     * Tampilkan profil pelanggan.
     */
    public function profile(Request $request): View
    {
        return view('customer.profile', ['user' => $request->user()]);
    }

    /**
     * Update profil dan nomor telepon pelanggan.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
    }
}
