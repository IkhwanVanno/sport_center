<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'slug', 'address', 'phone', 'is_active'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
