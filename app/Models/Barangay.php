<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;
    public function cityMunicipality() {
        return $this->belongsTo(CityMunicipality::class,'cityMunicipalityCode');    
    }
    public function province() {
        return $this->belongsTo(Province::class,'provincialCode');    
    }
    public function region() {
        return $this->belongsTo(Region::class,'regionCode');    
    }
    public function addresses() {
        return $this->belongsTo(Address::class,'address_id');    
    }
}
