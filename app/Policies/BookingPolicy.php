<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar booking/pesanan.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isOperator() || $user->isCustomer();
    }

    /**
     * Menentukan apakah pengguna dapat melihat detail booking.
     * Mencegah IDOR:
     * - Admin / Operator Cabang hanya boleh melihat pesanan di cabangnya.
     * - Customer hanya boleh melihat pesanannya sendiri.
     */
    public function view(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin() || $user->isOperator()) {
            return $booking->court?->branch_id === $user->branch_id;
        }

        if ($user->isCustomer()) {
            return $booking->user_id === $user->id;
        }

        return false;
    }

    /**
     * Menentukan apakah pengguna dapat mengubah atau membatalkan pesanan.
     */
    public function update(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $booking->court?->branch_id === $user->branch_id;
        }

        return false;
    }

    /**
     * Menentukan apakah pengguna dapat memverifikasi/mengonfirmasi pembayaran.
     * Mencegah IDOR: Admin Cabang hanya boleh konfirmasi pembayaran di cabangnya.
     */
    public function confirmPayment(User $user, Booking $booking): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $booking->court?->branch_id === $user->branch_id;
        }

        return false;
    }
}
