<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;

class FacilityPolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar fasilitas master.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna dapat melihat detail fasilitas.
     */
    public function view(User $user, Facility $facility): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Hanya Superadmin yang boleh membuat master fasilitas baru.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Hanya Superadmin yang boleh mengubah master fasilitas.
     */
    public function update(User $user, Facility $facility): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Hanya Superadmin yang boleh menghapus master fasilitas.
     */
    public function delete(User $user, Facility $facility): bool
    {
        return $user->isSuperAdmin();
    }
}
