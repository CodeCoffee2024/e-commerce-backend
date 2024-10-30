<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;    
    
    protected $fillable = ['name', 'price', 'quantity'];
    public function category() {
        return $this->belongsTo(Category::class,'category_id');    
    }
    public function merchant() {
        return $this->belongsTo(Merchant::class,'merchant_id');    
    }

    public function pickupAddress() {
        return $this->belongsTo(MerchantAddress::class, 'pickup_address_id');    
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function returnAddress() {
        return $this->belongsTo(MerchantAddress::class, 'return_address_id');    
    }
    public function shippingMatrix($location = null) {
        $pickupId = CityMunicipality::where('code', $this->pickupAddress->barangay->cityMunicipalityCode)->first();
        $returnId = CityMunicipality::where('code', $this->returnAddress->barangay->cityMunicipalityCode)->first();
        if ($location) {
            return ShippingMatrix::where('origin_cityMunicipality_id', $pickupId->id)
            ->where('destination_cityMunicipality_id', $location)
            ->first();
        }
        return ShippingMatrix::where('origin_cityMunicipality_id', $pickupId->id)
        ->first();
    }
}