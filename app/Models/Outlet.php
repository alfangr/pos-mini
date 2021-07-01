<?php

namespace App\Models;

use App\Models\Merchant;
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

    public function hasMerchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }
}
