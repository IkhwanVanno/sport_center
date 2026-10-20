<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Court;
use App\Models\CourtPriceRule;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PriceRuleService
{
    /**
     * Mengambil daftar aturan tarif dengan pembatasan hak akses cabang.
     */
    public function listPriceRules(User $user, ?int $courtId = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = CourtPriceRule::with(['court.branch'])
            ->forUser($user);

        if ($courtId) {
            $query->where('court_id', $courtId);
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Mengambil daftar lapangan yang diizinkan untuk diatur tarifnya.
     */
    public function getCourtOptions(User $user): Collection
    {
        $query = Court::where('is_active', true)->with('branch');

        if ($user->isAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Menyimpan aturan tarif baru dengan validasi integritas cabang.
     */
    public function storePriceRule(User $user, array $data): CourtPriceRule
    {
        return DB::transaction(function () use ($user, $data): CourtPriceRule {
            // Isolasi wewenang: Admin Cabang hanya boleh membuat tarif untuk lapangannya
            if ($user->isAdmin()) {
                $court = Court::findOrFail($data['court_id']);
                if ($court->branch_id !== $user->branch_id) {
                    throw ValidationException::withMessages([
                        'court_id' => 'Anda tidak memiliki akses untuk mengatur tarif pada lapangan cabang lain.',
                    ]);
                }
            }

            return CourtPriceRule::create($data);
        });
    }

    /**
     * Menghapus aturan tarif.
     */
    public function deletePriceRule(CourtPriceRule $priceRule): void
    {
        DB::transaction(function () use ($priceRule): void {
            $priceRule->delete();
        });
    }
}
