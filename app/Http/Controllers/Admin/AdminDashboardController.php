<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService
    ) {}

    /**
     * Menampilkan halaman ringkasan metrik dashboard admin.
     * Metrik otomatis terisolasi berdasarkan role (Superadmin vs Admin Cabang).
     */
    public function index(Request $request): View
    {
        $metrics = $this->dashboardService->getDashboardMetrics($request->user());

        return view('admin.dashboard', $metrics);
    }
}
