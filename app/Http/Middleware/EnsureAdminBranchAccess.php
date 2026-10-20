<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminBranchAccess
{
    /**
     * Memastikan bahwa pengguna dengan role 'admin' (Admin Cabang)
     * jika terikat pada cabang, cabangnya berstatus aktif.
     * Superadmin memiliki hak akses global penuh.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Akses ditolak: Pengguna tidak terautentikasi.');
        }

        // Superadmin memiliki akses global tanpa batasan cabang
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Jika user adalah Admin Cabang yang terikat pada cabang, pastikan cabang aktif
        if ($user->isAdmin() && $user->branch_id) {
            if (! $user->branch || ! $user->branch->is_active) {
                abort(403, 'Cabang tempat Anda bertugas saat ini sedang non-aktif atau tidak ditemukan.');
            }
        }

        return $next($request);
    }
}
