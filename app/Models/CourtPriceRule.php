<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtPriceRule extends Model
{
    protected $fillable = ['court_id', 'day_of_week', 'start_time', 'end_time', 'price_per_hour'];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
