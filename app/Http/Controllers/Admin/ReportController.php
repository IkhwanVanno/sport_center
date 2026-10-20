<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    /**
     * Menampilkan laporan omzet pendapatan dengan scoping cabang.
     */
    public function revenue(Request $request): View
    {
        Gate::authorize('viewAny', Booking::class);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $branchId = $request->filled('branch_id') ? (int) $request->input('branch_id') : null;

        $reportData = $this->reportService->getRevenueReport(
            $request->user(),
            (string) $startDate,
            (string) $endDate,
            $branchId
        );

        return view('admin.reports.revenue', [
            'reports' => $reportData['reports'],
            'totalRevenue' => $reportData['totalRevenue'],
            'branches' => $reportData['branches'],
            'startDate' => $reportData['startDate'],
            'endDate' => $reportData['endDate'],
            'branchId' => $reportData['selectedBranchId'],
        ]);
    }
}
