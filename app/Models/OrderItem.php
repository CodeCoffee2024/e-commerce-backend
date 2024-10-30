<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false; 
    use HasFactory;
    protected $fillable = ['order_id', 'price', 'quantity', 'total_price', 'product_id', 'status'];
    public function order() {
        return $this->belongsTo(Order::class,'order_id');    
    }
    public function product() {
        return $this->belongsTo(Product::class,'product_id');    
    }

    public function orderLogs() {
        return $this->hasMany(OrderLog::class, 'order_item_id');
    }
}
