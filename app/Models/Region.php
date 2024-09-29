<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    public function barangays() {
        return $this->hasMany(Barangay::class);
    }
    public function cityMunicipalities() {
        return $this->hasMany(CityMunicipality::class);
    }
    public function provinces() {
        return $this->hasMany(Province::class);
    }
}
