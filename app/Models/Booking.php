<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'court_id',
        'booking_date',
        'total_amount',
        'status',
        'payment_status',
        'expired_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope query berdasarkan user yang login (Role Scoping):
     * - Superadmin: melihat semua booking dari seluruh cabang.
     * - Admin Cabang / Operator: hanya melihat booking pada lapangan cabangnya.
     * - Customer: hanya melihat booking miliknya sendiri.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        if ($user->isAdmin() || $user->isOperator()) {
            return $query->whereHas('court', function (Builder $q) use ($user) {
                $q->where('branch_id', $user->branch_id);
            });
        }

        return $query->where('user_id', $user->id);
    }

    /**
     * Scope query berdasarkan ID cabang spesifik.
     */
    public function scopeForBranch(Builder $query, ?int $branchId): Builder
    {
        if ($branchId) {
            $query->whereHas('court', function (Builder $q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        return $query;
    }
}
