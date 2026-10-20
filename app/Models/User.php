<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone_number',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Cabang (khusus admin cabang / operator).
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relasi ke Riwayat Booking (pelanggan).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi ke Log Aktivitas.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Cek apakah user memiliki salah satu role yang ditentukan.
     */
    public function hasRole(string|array $roles): bool
    {
        $allowed = is_array($roles) ? $roles : explode('|', $roles);

        return in_array($this->role, array_map('trim', $allowed), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Scope query berdasarkan ID cabang.
     */
    public function scopeForBranch($query, ?int $branchId)
    {
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query;
    }

    /**
     * Scope query berdasarkan role pengguna.
     */
    public function scopeFilterRole($query, ?string $role)
    {
        if ($role) {
            $query->where('role', $role);
        }

        return $query;
    }

    /**
     * Scope pencarian nama, email, atau nomor telepon.
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone_number', 'like', "%{$keyword}%");
            });
        }

        return $query;
    }
}
