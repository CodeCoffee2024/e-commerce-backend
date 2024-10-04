<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;
    protected $fillable = ['description'];
    public function cityMunicipality()
    {
        return $this->belongsTo(CityMunicipality::class, 'cityMunicipalityCode', 'code');
    }
    public function province() {
        return $this->belongsTo(Province::class,'provincialCode', 'code');    
    }
    public function region() {
        return $this->belongsTo(Region::class,'regionCode', 'code');    
    }
    public function addresses() {
        return $this->belongsTo(Address::class,'address_id');    
    }
}
