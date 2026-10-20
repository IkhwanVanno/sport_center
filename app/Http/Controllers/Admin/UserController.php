<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Menampilkan daftar semua pengguna terdaftar dengan fitur filter role & cabang.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $role = $request->query('role');
        $branchId = $request->filled('branch_id') ? (int) $request->query('branch_id') : null;
        $search = $request->query('search');

        $users = $this->userService->listUsers($role, $branchId, $search, 10);
        $branches = $this->userService->getBranchOptions();

        return view('admin.users.index', compact('users', 'branches', 'role', 'branchId', 'search'));
    }

    /**
     * Menampilkan form untuk menambahkan pengguna baru.
     */
    public function create(): View
    {
        Gate::authorize('create', User::class);

        $branches = $this->userService->getBranchOptions();

        return view('admin.users.create', compact('branches'));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil ditambahkan ke dalam sistem.');
    }

    /**
     * Menampilkan form edit data pengguna.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $branches = $this->userService->getBranchOptions();

        return view('admin.users.edit', compact('user', 'branches'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus akun pengguna dari database.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $this->userService->deleteUser($request->user(), $user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    /**
     * Mereset password akun pengguna oleh Superadmin.
     */
    public function resetPassword(ResetPasswordRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('resetPassword', $user);

        $this->userService->resetPassword($user, $request->validated('password'));

        return redirect()
            ->back()
            ->with('success', "Password untuk akun {$user->name} ({$user->email}) berhasil diperbarui.");
    }
}
