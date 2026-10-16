<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin / superadmin.
     */
    public function index(Request $request): View
    {
        return view('admin.dashboard');
    }
}
