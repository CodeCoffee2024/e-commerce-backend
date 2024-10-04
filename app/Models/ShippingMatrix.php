<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMatrix extends Model
{
    use HasFactory;
    protected $fillable = [
        'addressOrigin',
        'addressDestination',
        'address_origin_id',
        'address_destination_id',
        'price'
    ];

    public function addressOrigin() {
        return $this->belongsTo(Address::class,'address_origin_id');    
    }
    public function addressDestination() {
        return $this->belongsTo(Address::class,'address_destination_id');    
    }
    public static function defaultFee() {
        return 40.00;
    }
}
