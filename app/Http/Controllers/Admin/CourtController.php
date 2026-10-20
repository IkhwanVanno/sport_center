<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourtRequest;
use App\Models\Court;
use App\Services\CourtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourtController extends Controller
{
    public function __construct(
        protected CourtService $courtService
    ) {}

    /**
     * Menampilkan daftar lapangan sesuai hak akses user (scoping cabang).
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Court::class);

        $branchId = $request->filled('branch_id') ? (int) $request->query('branch_id') : null;
        $sportId = $request->filled('sport_id') ? (int) $request->query('sport_id') : null;

        $courts = $this->courtService->listCourts($request->user(), $branchId, $sportId, 10);
        $branches = $this->courtService->getBranchOptions($request->user());
        $sports = $this->courtService->getSportOptions();

        return view('admin.courts.index', compact('courts', 'branches', 'sports', 'branchId', 'sportId'));
    }

    /**
     * Menampilkan form pembuatan lapangan baru.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Court::class);

        $branches = $this->courtService->getBranchOptions($request->user());
        $sports = $this->courtService->getSportOptions();
        $facilities = $this->courtService->getFacilityOptions();

        return view('admin.courts.create', compact('branches', 'sports', 'facilities'));
    }

    /**
     * Menyimpan data lapangan baru ke database via service layer.
     */
    public function store(CourtRequest $request): RedirectResponse
    {
        $this->courtService->storeCourt($request->user(), $request->validated());

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Lapangan olahraga berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit lapangan.
     * Mencegah IDOR: Gate::authorize('update', $court).
     */
    public function edit(Request $request, Court $court): View
    {
        Gate::authorize('update', $court);

        $branches = $this->courtService->getBranchOptions($request->user());
        $sports = $this->courtService->getSportOptions();
        $facilities = $this->courtService->getFacilityOptions();
        $court->load('facilities');

        return view('admin.courts.edit', compact('court', 'branches', 'sports', 'facilities'));
    }

    /**
     * Memperbarui data lapangan via service layer.
     */
    public function update(CourtRequest $request, Court $court): RedirectResponse
    {
        Gate::authorize('update', $court);

        $this->courtService->updateCourt($request->user(), $court, $request->validated());

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    /**
     * Menghapus lapangan dari sistem.
     */
    public function destroy(Court $court): RedirectResponse
    {
        Gate::authorize('delete', $court);

        $this->courtService->deleteCourt($court);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
