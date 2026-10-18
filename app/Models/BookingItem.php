<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['booking_id', 'start_time', 'end_time', 'price'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
