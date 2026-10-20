<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use App\Services\FacilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FacilitiesController extends Controller
{
    public function __construct(
        protected FacilityService $facilityService
    ) {}

    /**
     * Menampilkan daftar fasilitas master.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Facility::class);

        $facilities = $this->facilityService->listFacilities();

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Menampilkan form penambahan fasilitas (Khusus Superadmin).
     */
    public function create(): View
    {
        Gate::authorize('create', Facility::class);
        $facilities = $this->facilityService->listFacilities();
        return view('admin.facilities.create', compact('facilities'));
    }

    /**
     * Menyimpan fasilitas baru (Khusus Superadmin).
     */
    public function store(FacilityRequest $request): RedirectResponse
    {
        $this->facilityService->storeFacility($request->validated());

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Master fasilitas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit fasilitas (Khusus Superadmin).
     */
    public function edit(Facility $facility): View
    {
        Gate::authorize('update', $facility);
        $facilities = $this->facilityService->listFacilities();
        return view('admin.facilities.edit', compact('facility', 'facilities'));
    }

    /**
     * Memperbarui data fasilitas (Khusus Superadmin).
     */
    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        Gate::authorize('update', $facility);

        $this->facilityService->updateFacility($facility, $request->validated());

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Master fasilitas berhasil diperbarui.');
    }

    /**
     * Menghapus fasilitas dari sistem (Khusus Superadmin).
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        Gate::authorize('delete', $facility);

        $this->facilityService->deleteFacility($facility);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Master fasilitas berhasil dihapus.');
    }
}
