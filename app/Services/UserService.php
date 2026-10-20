<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Mengambil daftar pengguna dengan filter role, cabang, dan kata kunci pencarian.
     */
    public function listUsers(
        ?string $role = null,
        ?int $branchId = null,
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return User::with('branch')
            ->filterRole($role)
            ->forBranch($branchId)
            ->search($search)
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Mengambil daftar seluruh cabang aktif untuk opsi penugasan akun staf.
     */
    public function getBranchOptions(): Collection
    {
        return Branch::orderBy('name')->get();
    }

    /**
     * Membuat akun pengguna baru.
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            // Pengguna dengan role selain admin dan operator tidak memerlukan asosiasi cabang
            if (! in_array($data['role'], ['admin', 'operator'], true)) {
                $data['branch_id'] = null;
            }

            $data['password'] = Hash::make($data['password']);

            return User::create($data);
        });
    }

    /**
     * Memperbarui informasi akun pengguna.
     */
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            if (! in_array($data['role'], ['admin', 'operator'], true)) {
                $data['branch_id'] = null;
            }

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $user;
        });
    }

    /**
     * Mereset password akun pengguna oleh Superadmin.
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        DB::transaction(function () use ($user, $newPassword): void {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);
        });
    }

    /**
     * Menghapus akun pengguna dari sistem.
     */
    public function deleteUser(User $actor, User $target): void
    {
        if ($actor->id === $target->id) {
            throw ValidationException::withMessages([
                'user' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ]);
        }

        DB::transaction(function () use ($target): void {
            $target->delete();
        });
    }
}
