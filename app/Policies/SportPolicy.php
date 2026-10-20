<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Sport;
use App\Models\User;

class SportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, Sport $sport): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Sport $sport): bool
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Sport $sport): bool
    {
        return $user->isSuperAdmin();
    }
}
