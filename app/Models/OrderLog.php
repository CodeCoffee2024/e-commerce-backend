<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $fillable = ['order_id', 'status', 'order_item_id'];
    
    use HasFactory;
    public function order() {
        return $this->belongsTo(Order::class,'order_id');    
    }
    public function orderItem() {
        return $this->belongsTo(OrderItem::class,'order_item_id');    
    }
}
