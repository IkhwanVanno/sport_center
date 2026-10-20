<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    protected $fillable = [
        'branch_id',
        'sport_id',
        'name',
        'slug',
        'base_price_per_hour',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price_per_hour' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class);
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(CourtPriceRule::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope query berdasarkan user yang login (Role Scoping):
     * - Superadmin: melihat semua lapangan tanpa batas cabang.
     * - Admin Cabang / Operator: hanya melihat lapangan pada cabang miliknya.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where('branch_id', $user->branch_id);
    }

    /**
     * Scope query berdasarkan ID cabang spesifik.
     */
    public function scopeForBranch(Builder $query, ?int $branchId): Builder
    {
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query;
    }
}
