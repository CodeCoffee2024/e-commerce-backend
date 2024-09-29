<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    public function barangays() {
        return $this->hasMany(Barangay::class);
    }
    public function cityMunicipalities() {
        return $this->hasMany(CityMunicipality::class);
    }
    public function region() {
        return $this->belongsTo(Region::class,'regionCode');    
    }
}
