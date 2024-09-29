<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityMunicipality extends Model
{
    use HasFactory;
    public function barangays() {
        return $this->hasMany(Barangay::class);
    }
    public function province() {
        return $this->belongsTo(Province::class,'provincialCode');    
    }
    public function region() {
        return $this->belongsTo(Region::class,'regionCode');    
    }
}
