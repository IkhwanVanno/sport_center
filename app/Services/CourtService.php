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
     * Mengambil daftar lapangan dengan filtering hak akses (Scoping Cabang).
     */
    public function listCourts(User $user, ?int $branchId = null, ?int $sportId = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Court::with(['branch', 'sport', 'facilities'])
            ->forUser($user);

        // Jika Superadmin memilih filter cabang spesifik
        if ($user->isSuperAdmin() && $branchId) {
            $query->forBranch($branchId);
        }

        if ($sportId) {
            $query->where('sport_id', $sportId);
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Mengambil daftar cabang untuk opsi form create/edit.
     */
    public function getBranchOptions(User $user): Collection
    {
        if ($user->isSuperAdmin()) {
            return Branch::where('is_active', true)->orderBy('name')->get();
        }

        return Branch::where('id', $user->branch_id)->get();
    }

    /**
     * Mengambil seluruh opsi jenis olahraga.
     */
    public function getSportOptions(): Collection
    {
        return Sport::orderBy('name')->get();
    }

    /**
     * Mengambil seluruh opsi fasilitas olahraga.
     */
    public function getFacilityOptions(): Collection
    {
        return Facility::orderBy('name')->get();
    }

    /**
     * Menyimpan data lapangan baru secara aman dengan transaksi database.
     */
    public function storeCourt(User $user, array $data): Court
    {
        return DB::transaction(function () use ($user, $data): Court {
            // Isolasi wewenang: Admin Cabang otomatis di-lock ke branch_id miliknya
            if ($user->isAdmin()) {
                $data['branch_id'] = $user->branch_id;
            }

            $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
            $data['is_active'] = $data['is_active'] ?? true;

            $facilities = $data['facilities'] ?? [];
            unset($data['facilities']);

            $court = Court::create($data);
            $court->facilities()->sync($facilities);

            return $court;
        });
    }

    /**
     * Memperbarui data lapangan secara aman.
     */
    public function updateCourt(User $user, Court $court, array $data): Court
    {
        return DB::transaction(function () use ($user, $court, $data): Court {
            // Isolasi wewenang: Admin Cabang tidak dapat memindahkan lapangan ke cabang lain
            if ($user->isAdmin()) {
                $data['branch_id'] = $user->branch_id;
            }

            if (isset($data['name']) && $data['name'] !== $court->name) {
                $data['slug'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));
            }

            $facilities = $data['facilities'] ?? [];
            unset($data['facilities']);

            $court->update($data);
            $court->facilities()->sync($facilities);

            return $court;
        });
    }

    /**
     * Menghapus lapangan beserta relasi fasilitasnya.
     */
    public function deleteCourt(Court $court): void
    {
        DB::transaction(function () use ($court): void {
            $court->facilities()->detach();
            $court->delete();
        });
    }
}
