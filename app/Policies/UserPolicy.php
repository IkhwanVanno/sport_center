<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Hanya Superadmin yang boleh melihat daftar manajemen akun pengguna.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Hanya Superadmin yang boleh melihat detail akun pengguna.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Hanya Superadmin yang boleh menambahkan akun baru.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Hanya Superadmin yang boleh memperbarui akun pengguna.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Superadmin dapat menghapus akun, namun tidak boleh menghapus akunnya sendiri.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isSuperAdmin() && $user->id !== $model->id;
    }

    /**
     * Hanya Superadmin yang boleh mereset password akun pengguna lain.
     */
    public function resetPassword(User $user, User $model): bool
    {
        return $user->isSuperAdmin();
    }
}
