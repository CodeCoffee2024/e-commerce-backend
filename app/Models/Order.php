<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    static $paymentOptions = ['CashOnDelivery'];
    protected $fillable = ['shipping_address_id','user_id', 'cart', 'paymentOption', 'totalPrice', 'totalShipping', 'status'];
    public function shippingMerchants()
    {
        return $this->belongsToMany(ShippingMerchant::class, 'order_shipping_merchant', 'order_id', 'merchant_address_id')
                    ->withPivot('shipping_cost')
                    ->withTimestamps();
    }
    public function getTotalShippingAttribute()
    {
        return $this->shippingMerchants->sum('pivot.shipping_cost');
    }
}
