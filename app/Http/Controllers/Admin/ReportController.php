<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $branchId = $request->input('branch_id');

        $query = Booking::where('payment_status', 'paid')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->with(['court.branch', 'user']);

        if ($branchId) {
            $query->whereHas('court', fn($q) => $q->where('branch_id', $branchId));
        }

        $reports = $query->get();
        $totalRevenue = $reports->sum('total_amount');
        $branches = Branch::all();

        return view('admin.reports.revenue', compact('reports', 'totalRevenue', 'branches', 'startDate', 'endDate', 'branchId'));
    }
}
