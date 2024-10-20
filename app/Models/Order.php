<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    static $paymentOptions = ['CashOnDelivery'];
    protected $fillable = ['shipping_address_id','user_id', 'cart', 'paymentOption', 'totalPrice', 'totalShipping', 'status', 'reference_number'];
    public function shippingMerchants()
    {
        return $this->belongsToMany(MerchantAddress::class, 'order_shipping_merchants', 'order_id', 'merchant_address_id')
                    ->withPivot('shipping_cost')
                    ->withTimestamps();
    }
    public function user() {
        return $this->belongsTo(User::class,'user_id');    
    }
    public function shippingAddress() {
        return $this->belongsTo(Address::class,'shipping_address_id');    
    }
    public function getTotalShippingAttribute()
    {
        return $this->shippingMerchants->sum('pivot.shipping_cost');
    }
}
