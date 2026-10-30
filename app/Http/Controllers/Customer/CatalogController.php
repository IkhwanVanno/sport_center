<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Court;
use App\Models\Sport;
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
            ->latest()
            ->take(6)
            ->get();

        return view('public.home', compact('courts'));
    }

    /**
     * Tampilkan daftar lapangan dengan filter langsung di database.
     */
    public function list(Request $request): View
    {
        $validated = $request->validate([
            'search'   => ['nullable', 'string', 'max:100'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'sport_id'  => ['nullable', 'integer', 'exists:sports,id'],
            'category'  => ['nullable', 'string', 'max:100'],
            'capacity'  => ['nullable', 'integer', 'min:1'],
        ]);

        $query = Court::query()
            ->with(['branch', 'sport', 'facilities'])
            ->where('is_active', true);

        // Pencarian nama lapangan langsung di database.
        $query->when($validated['search'] ?? null, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });

        // Filter berdasarkan cabang.
        $query->when($validated['branch_id'] ?? null, function ($query, $branchId) {
            $query->where('branch_id', $branchId);
        });

        // Filter berdasarkan jenis olahraga.
        $query->when($validated['sport_id'] ?? null, function ($query, $sportId) {
            $query->where('sport_id', $sportId);
        });

        // Filter berdasarkan kategori lapangan.
        $query->when($validated['category'] ?? null, function ($query, $category) {
            $query->where('category', $category);
        });

        // Filter kapasitas minimal.
        $query->when($validated['capacity'] ?? null, function ($query, $capacity) {
            $query->where('capacity', '>=', $capacity);
        });

        // Pagination dilakukan oleh database.
        $courts = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

        // Ambil pilihan filter secara independen dari hasil pencarian.
        $branches = Branch::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $sports = Sport::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Court::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('public.courts.index', compact(
            'courts',
            'branches',
            'sports',
            'categories'
        ));
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
    public function checkAvailability(
        Request $request,
        Court $court,
        AvailabilityService $availability
    ): JsonResponse {
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
            'available_count' => count(array_filter(
                $slots,
                fn(array $slot) => $slot['status'] === 'available'
            )),
        ]);
    }
}
