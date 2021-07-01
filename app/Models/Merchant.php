<?php

namespace App\Models;

use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone'
    ];

    public function hasOutlets() {
        return $this->hasMany(Outlet::class, 'merchant_id', 'id');
    }
}
