<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/products.json");
        $products = json_decode($json);
        foreach ($products as $key => $value) {
            Product::create([
                "name" => $value->name,
                "description" => $value->description,
                "category_id" => $value->category_id,
                "merchant_id" => $value->merchant_id,
                "price" => $value->price,
                "quantity" => $value->quantity,
                "isActive" => 1,
                "imgPath" => 'uploads/product.jfif',

            ]);
        }
    }
}
