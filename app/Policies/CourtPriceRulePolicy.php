<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CourtPriceRule;
use App\Models\User;

class CourtPriceRulePolicy
{
    /**
     * Menentukan apakah pengguna dapat melihat daftar aturan tarif.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Menentukan apakah pengguna dapat melihat aturan tarif tertentu.
     * Mencegah IDOR: Admin Cabang hanya boleh melihat tarif lapangan di cabangnya.
     */
    public function view(User $user, CourtPriceRule $rule): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $rule->court?->branch_id === $user->branch_id;
    }

    /**
     * Menentukan apakah pengguna dapat membuat aturan tarif.
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && ! empty($user->branch_id);
    }

    /**
     * Menentukan apakah pengguna dapat menghapus aturan tarif.
     * Mencegah IDOR: Admin Cabang hanya boleh menghapus tarif untuk lapangan cabangnya.
     */
    public function delete(User $user, CourtPriceRule $rule): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $rule->court?->branch_id === $user->branch_id;
    }
}
