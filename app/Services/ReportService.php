<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Mengambil laporan omzet pendapatan dengan pembatasan hak akses cabang.
     *
     * @return array{
     *     reports: Collection,
     *     totalRevenue: float,
     *     branches: Collection,
     *     startDate: string,
     *     endDate: string,
     *     selectedBranchId: int|null
     * }
     */
    public function getRevenueReport(User $user, string $startDate, string $endDate, ?int $branchId = null): array
    {
        // Isolasi Cabang: Admin Cabang terkunci hanya pada cabangnya sendiri
        $selectedBranchId = $user->isAdmin() ? $user->branch_id : $branchId;

        $query = Booking::where('payment_status', 'paid')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->with(['court.branch', 'user']);

        if ($selectedBranchId) {
            $query->whereHas('court', fn ($q) => $q->where('branch_id', $selectedBranchId));
        }

        $reports = $query->latest('booking_date')->get();
        $totalRevenue = (float) $reports->sum('total_amount');

        // Daftar cabang yang boleh dipilih di filter dropdown
        $branches = $user->isSuperAdmin()
            ? Branch::orderBy('name')->get()
            : Branch::where('id', $user->branch_id)->get();

        return [
            'reports' => $reports,
            'totalRevenue' => $totalRevenue,
            'branches' => $branches,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedBranchId' => $selectedBranchId,
        ];
    }
}
