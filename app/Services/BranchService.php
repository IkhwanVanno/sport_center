<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchService
{
    /**
     * Mengambil daftar seluruh cabang dengan jumlah lapangan terkait.
     */
    public function listBranches(int $perPage = 10): LengthAwarePaginator
    {
        return Branch::withCount('courts')
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Menyimpan cabang baru ke database.
     */
    public function storeBranch(array $data): Branch
    {
        return DB::transaction(function () use ($data): Branch {
            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = $data['is_active'] ?? true;

            return Branch::create($data);
        });
    }

    /**
     * Memperbarui informasi cabang.
     */
    public function updateBranch(Branch $branch, array $data): Branch
    {
        return DB::transaction(function () use ($branch, $data): Branch {
            if (isset($data['name']) && $data['name'] !== $branch->name) {
                $data['slug'] = Str::slug($data['name']);
            }

            $branch->update($data);

            return $branch;
        });
    }

    /**
     * Menghapus cabang dari database.
     */
    public function deleteBranch(Branch $branch): void
    {
        DB::transaction(function () use ($branch): void {
            $branch->delete();
        });
    }
}
