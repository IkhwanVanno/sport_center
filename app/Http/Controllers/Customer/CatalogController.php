<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Tampilkan halaman utama katalog / beranda publik.
     */
    public function index(Request $request): View
    {
        $courts = Court::query()
            ->with(['branch', 'sport', 'facilities'])
            ->where('is_active', true)
            ->take(6)
            ->get();

        return view('public.home', compact('courts'));
    }

    /**
     * Tampilkan daftar lapangan aktif.
     */
    public function list(Request $request): View
    {
        $courts = Court::query()
            ->with(['branch', 'sport', 'facilities'])
            ->where('is_active', true)
            ->get();

        return view('public.courts.index', compact('courts'));
    }

    /**
     * Tampilkan detail lapangan tertentu.
     */
    public function show(Court $court): View
    {
        $court->load(['branch', 'sport', 'facilities']);

        return view('public.courts.show', compact('court'));
    }

    /**
     * Cek ketersediaan slot lapangan pada tanggal tertentu.
     */
    public function checkAvailability(Request $request, Court $court, AvailabilityService $availability): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $slots = $availability->getSlots($court, $validated['date']);

        return response()->json([
            'court_id' => $court->id,
            'court_name' => $court->name,
            'date' => $validated['date'],
            'open_time' => AvailabilityService::OPEN_TIME,
            'close_time' => AvailabilityService::CLOSE_TIME,
            'slots' => $slots,
            'available_count' => count(array_filter($slots, fn (array $slot) => $slot['status'] === 'available')),
        ]);
    }
}
