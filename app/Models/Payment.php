<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id', 'payment_method', 'transaction_id', 'amount', 'proof_of_payment', 'status', 'paid_at'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
