<?php

declare(strict_types=1);

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckInController extends Controller
{
    /**
     * Tampilkan halaman check-in operator.
     */
    public function index(Request $request): View
    {
        return view('operator.checkin');
    }

    /**
     * Verifikasi kode booking.
     */
    public function verify(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Verifikasi berhasil']);
    }

    /**
     * Simpan pemesanan walk-in.
     */
    public function storeWalkIn(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Booking walk-in berhasil']);
    }
}
