<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Court;
use App\Models\User;

class CourtPolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar lapangan.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna dapat melihat detail lapangan.
     * Mencegah IDOR: Admin Cabang hanya boleh melihat lapangan di cabangnya.
     */
    public function view(User $user, Court $court): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $court->branch_id === $user->branch_id;
    }

    /**
     * Menentukan apakah pengguna dapat menambahkan lapangan.
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && ! empty($user->branch_id);
    }

    /**
     * Menentukan apakah pengguna dapat memperbarui lapangan.
     * Mencegah IDOR: Admin Cabang hanya boleh mengubah lapangan di cabangnya.
     */
    public function update(User $user, Court $court): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $court->branch_id === $user->branch_id;
    }

    /**
     * Menentukan apakah pengguna dapat menghapus lapangan.
     * Mencegah IDOR: Admin Cabang hanya boleh menghapus lapangan di cabangnya.
     */
    public function delete(User $user, Court $court): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $court->branch_id === $user->branch_id;
    }
}
