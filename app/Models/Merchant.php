<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'imgPath'];
    public function addresses() {
        return $this->hasMany(MerchantAddress::class, 'merchant_id');
    }
    public function products() {
        return $this->hasMany(Product::class, 'merchant_id');
    }
    public function shippingMerchants() {
        return $this->hasMany(ShippingMerchant::class);
    }
}
