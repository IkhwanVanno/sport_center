<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_amount');
        $todayBookings = Booking::whereDate('booking_date', today())->count();
        $pendingVerifications = Booking::where('payment_status', 'pending_verification')->count();
        $activeCourts = Court::where('is_active', true)->count();

        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'todayBookings',
            'pendingVerifications',
            'activeCourts',
            'recentBookings'
        ));
    }
}
