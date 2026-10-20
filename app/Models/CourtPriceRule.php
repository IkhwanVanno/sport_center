<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtPriceRule extends Model
{
    protected $fillable = [
        'court_id',
        'day_of_week',
        'start_time',
        'end_time',
        'price_per_hour',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'price_per_hour' => 'decimal:2',
        ];
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Scope query berdasarkan user yang login (Role Scoping):
     * - Superadmin: melihat semua aturan tarif.
     * - Admin Cabang: hanya melihat aturan tarif untuk lapangan di cabangnya.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->whereHas('court', function (Builder $q) use ($user) {
            $q->where('branch_id', $user->branch_id);
        });
    }
}
