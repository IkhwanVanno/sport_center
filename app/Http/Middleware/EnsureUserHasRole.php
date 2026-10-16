<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Memastikan pengguna memiliki salah satu role yang diizinkan route.
     * Mendukung pemisah pipe (|) dan koma (,), misalnya:
     * ->middleware('role:customer')
     * ->middleware('role:operator|superadmin')
     * ->middleware('role:admin,superadmin')
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        $allowedRoles = [];
        foreach ($roles as $roleGroup) {
            foreach (explode('|', $roleGroup) as $pipeItem) {
                foreach (explode(',', $pipeItem) as $role) {
                    $trimmed = trim($role);
                    if ($trimmed !== '') {
                        $allowedRoles[] = $trimmed;
                    }
                }
            }
        }

        if (! in_array($user->role, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
