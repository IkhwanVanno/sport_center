<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $branchService
    ) {}

    /**
     * Menampilkan daftar semua cabang sport center (Khusus Superadmin).
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Branch::class);

        $branches = $this->branchService->listBranches(10);

        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Menampilkan form tambah cabang baru (Khusus Superadmin).
     */
    public function create(): View
    {
        Gate::authorize('create', Branch::class);

        return view('admin.branches.create');
    }

    /**
     * Menyimpan cabang baru ke database.
     */
    public function store(BranchRequest $request): RedirectResponse
    {
        $this->branchService->storeBranch($request->validated());

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Cabang sport center berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit cabang (Khusus Superadmin).
     */
    public function edit(Branch $branch): View
    {
        Gate::authorize('update', $branch);

        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Memperbarui data cabang di database.
     */
    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        Gate::authorize('update', $branch);

        $this->branchService->updateBranch($branch, $request->validated());

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    /**
     * Menghapus cabang dari database.
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        Gate::authorize('delete', $branch);

        $this->branchService->deleteBranch($branch);

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}