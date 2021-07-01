<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = [
        'user_id',
        'merchant_id',
        'name',
        'address',
        'phone'
    ];
}
