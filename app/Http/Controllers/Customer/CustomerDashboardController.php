<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard pelanggan.
     */
    public function index(Request $request): View
    {
        return view('customer.dashboard');
    }
}
