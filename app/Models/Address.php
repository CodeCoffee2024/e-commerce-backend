<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'blockLotFloorBuildingName',
        'streetAddress',
        'zipCode',
        'isMainDeliveryAddress',
        'name',
        'contactNumber',
        'isActive',
        'barangay_id'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id');    
    }
    public function barangay() {
        return $this->belongsTo(Barangay::class,'barangay_id');    
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
}
