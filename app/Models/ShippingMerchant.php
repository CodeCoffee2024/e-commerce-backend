<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMerchant extends Model
{
    use HasFactory;
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_shipping_merchant')
                    ->withPivot('shipping_cost')
                    ->withTimestamps();
    }
}
