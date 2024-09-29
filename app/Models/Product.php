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
}