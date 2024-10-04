<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'blockLotFloorBuildingName',
        'streetAddress',
        'zipCode',
        'isMainReturnAddress',
        'isMainPickupAddress',
        'isActive',
        'barangay_id',
        'merchant_id',
    ];

    public function merchant() {
        return $this->belongsTo(Merchant::class,'merchant_id');    
    }

    public function barangay() {
        return $this->belongsTo(Barangay::class,'barangay_id');    
    }

    public function pickupProducts() {
        return $this->hasMany(Product::class, 'pickup_address_id');
    }

    public function returnProducts() {
        return $this->hasMany(Product::class, 'return_address_id');
    }
}
