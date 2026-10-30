<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Branch;
use App\Models\Court;
use App\Models\Facility;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourtService
{
    /**
     * Mengambil daftar lapangan dengan filtering hak akses.
     */
    public function listCourts(
        User $user,
        ?int $branchId = null,
        ?int $sportId = null,
        int $perPage = 10,
        ?string $category = null,
        ?int $capacity = null
    ): LengthAwarePaginator {
        $query = Court::query()
            ->with(['branch', 'sport', 'facilities'])
            ->forUser($user);

        // Filter cabang hanya untuk Superadmin.
        if ($user->isSuperAdmin() && $branchId) {
            $query->forBranch($branchId);
        }

        // Filter jenis olahraga.
        if ($sportId) {
            $query->where('sport_id', $sportId);
        }

        // Filter kategori.
        if ($category) {
            $query->where('category', $category);
        }

        // Filter kapasitas minimal.
        if ($capacity !== null) {
            $query->where('capacity', '>=', $capacity);
        }

        return $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Mengambil opsi cabang.
     */
    public function getBranchOptions(User $user): Collection
    {
        if ($user->isSuperAdmin()) {
            return Branch::where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return Branch::where('id', $user->branch_id)->get();
    }

    /**
     * Mengambil opsi olahraga.
     */
    public function getSportOptions(): Collection
    {
        return Sport::orderBy('name')->get();
    }

    /**
     * Mengambil opsi fasilitas.
     */
    public function getFacilityOptions(): Collection
    {
        return Facility::orderBy('name')->get();
    }

    /**
     * Mengambil opsi kategori lapangan.
     */
    public function getCategoryOptions(): Collection
    {
        return Court::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * Menyimpan lapangan baru.
     */
    public function storeCourt(User $user, array $data): Court
    {
        return DB::transaction(function () use ($user, $data): Court {
            // Admin cabang tidak boleh menentukan cabang lain.
            if ($user->isAdmin()) {
                $data['branch_id'] = $user->branch_id;
            }

            $data['slug'] = Str::slug($data['name'])
                . '-'
                . Str::lower(Str::random(5));

            $data['is_active'] = $data['is_active'] ?? true;

            $facilities = $data['facilities'] ?? [];

            unset($data['facilities']);

            $court = Court::create($data);

            $court->facilities()->sync($facilities);

            return $court;
        });
    }

    /**
     * Memperbarui lapangan.
     */
    public function updateCourt(
        User $user,
        Court $court,
        array $data
    ): Court {
        return DB::transaction(function () use ($user, $court, $data): Court {
            // Admin cabang tetap terkunci pada cabangnya sendiri.
            if ($user->isAdmin()) {
                $data['branch_id'] = $user->branch_id;
            }

            if (
                isset($data['name'])
                && $data['name'] !== $court->name
            ) {
                $data['slug'] = Str::slug($data['name'])
                    . '-'
                    . Str::lower(Str::random(5));
            }

            $facilities = $data['facilities'] ?? [];

            unset($data['facilities']);

            $court->update($data);

            $court->facilities()->sync($facilities);

            return $court;
        });
    }

    /**
     * Menghapus lapangan beserta relasi fasilitas.
     */
    public function deleteCourt(Court $court): void
    {
        DB::transaction(function () use ($court): void {
            $court->facilities()->detach();

            $court->delete();
        });
    }
}
