<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FacilityService
{
    /**
     * Mengambil seluruh data fasilitas master.
     */
    public function listFacilities(): Collection
    {
        return Facility::withCount('courts')->orderBy('name')->get();
    }

    /**
     * Menyimpan data master fasilitas baru.
     */
    public function storeFacility(array $data): Facility
    {
        return DB::transaction(function () use ($data): Facility {
            return Facility::create($data);
        });
    }

    /**
     * Memperbarui data master fasilitas.
     */
    public function updateFacility(Facility $facility, array $data): Facility
    {
        return DB::transaction(function () use ($facility, $data): Facility {
            $facility->update($data);
            return $facility;
        });
    }

    /**
     * Menghapus master fasilitas.
     */
    public function deleteFacility(Facility $facility): void
    {
        DB::transaction(function () use ($facility): void {
            $facility->courts()->detach();
            $facility->delete();
        });
    }
}
