<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = ['branch_id', 'sport_id', 'name', 'slug', 'base_price_per_hour', 'description', 'is_active'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
    public function priceRules()
    {
        return $this->hasMany(CourtPriceRule::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
