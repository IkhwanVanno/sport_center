<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['branch_id','name','email','phone_number','password','role']; protected $hidden = ['password','remember_token'];

    public function branch(){ return $this->belongsTo(Branch::class); } public function bookings(){ return $this->hasMany(Booking::class); } public function activityLogs(){ return $this->hasMany(ActivityLog::class); }
}
