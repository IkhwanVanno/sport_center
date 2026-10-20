<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderManagementService
{
    /**
     * Mengambil daftar pesanan/booking dengan filtering hak akses cabang.
     */
    public function listOrders(
        User $user,
        ?string $status = null,
        ?string $paymentStatus = null,
        ?int $branchId = null,
        ?string $search = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Booking::with(['user', 'court.branch', 'items', 'payments'])
            ->forUser($user);

        // Jika Superadmin memilih filter cabang tertentu
        if ($user->isSuperAdmin() && $branchId) {
            $query->forBranch($branchId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Konfirmasi pembayaran pesanan secara manual dengan perlindungan race conditions (lockForUpdate).
     */
    public function confirmPayment(Booking $booking): void
    {
        DB::transaction(function () use ($booking): void {
            // Pessimistic locking untuk menjamin integritas data
            $lockedBooking = Booking::where('id', $booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedBooking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            $payment = $lockedBooking->payments()->latest()->lockForUpdate()->first();
            if ($payment) {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);
            }
        });
    }
}
