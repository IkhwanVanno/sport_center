<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    /**
     * Mengambil metrik statistik dashboard yang disesuaikan dengan role dan isolasi cabang.
     *
     * @return array{
     *     totalRevenue: float,
     *     todayBookings: int,
     *     pendingVerifications: int,
     *     activeCourts: int,
     *     recentBookings: Collection
     * }
     */
    public function getDashboardMetrics(User $user): array
    {
        $revenueQuery = Booking::where('payment_status', 'paid')->forUser($user);
        $totalRevenue = (float) $revenueQuery->sum('total_amount');

        $todayBookings = Booking::whereDate('booking_date', today())
            ->forUser($user)
            ->count();

        $pendingVerifications = Booking::where('payment_status', 'pending_verification')
            ->forUser($user)
            ->count();

        $activeCourts = Court::where('is_active', true)
            ->forUser($user)
            ->count();

        $recentBookings = Booking::with(['user', 'court.branch'])
            ->forUser($user)
            ->latest('id')
            ->take(5)
            ->get();

        return [
            'totalRevenue' => $totalRevenue,
            'todayBookings' => $todayBookings,
            'pendingVerifications' => $pendingVerifications,
            'activeCourts' => $activeCourts,
            'recentBookings' => $recentBookings,
        ];
    }
}
