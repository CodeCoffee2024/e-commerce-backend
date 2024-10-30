<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    static $paymentOptions = ['CashOnDelivery'];
    static $PAYMENT_OPTION_CASH_ON_DELIVERY = 'CashOnDelivery';
    const STATUS_PENDING = 'pendi';
    const STATUS_TO_SHIP = 'toShi';
    const STATUS_FOR_DELIVERY = 'forDe';
    const STATUS_RECEIVED = 'recei';
    const STATUS_LABELS = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_TO_SHIP => 'To Ship',
        self::STATUS_FOR_DELIVERY => 'For Delivery',
        self::STATUS_RECEIVED => 'Received'
    ];
    const STATUS_DESCRIPTION = [
        self::STATUS_PENDING => 'Order has been placed.',
        self::STATUS_TO_SHIP => 'Order has been shipped.',
        self::STATUS_FOR_DELIVERY => 'Order is out for delivery.',
        self::STATUS_RECEIVED => 'Order is completed.'
    ];
    public static $paymentOptionLabels = [
        'CashOnDelivery' => 'Cash On Delivery'
    ];
    protected $fillable = ['shipping_address_id','user_id', 'cart', 'paymentOption', 'totalPrice', 'totalShipping', 'status', 'reference_number'];
    public function shippingMerchants()
    {
        return $this->belongsToMany(MerchantAddress::class, 'order_shipping_merchants', 'order_id', 'merchant_address_id')
                    ->withPivot('shipping_cost')
                    ->withTimestamps();
    }
    public function merchants()
    {
        return $this->belongsToMany(MerchantAddress::class, 'order_shipping_merchants', 'order_id', 'merchant_address_id')
                    ->withTimestamps();
    }
    public function user() {
        return $this->belongsTo(User::class,'user_id');    
    }
    public function shippingAddress() {
        return $this->belongsTo(Address::class,'shipping_address_id');    
    }
    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function orderLogs() {
        return $this->hasMany(OrderLog::class, 'order_item_id');
    }
    // public function getTotalShippingAttribute()
    // {
    //     return $this->shippingMerchants->sum('pivot.shipping_cost');
    // }
}
