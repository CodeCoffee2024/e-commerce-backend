<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/category.json");
        $categories = json_decode($json);
        foreach ($categories as $key => $value) {
            Category::create([
                "name" => $value->name,
                "description" => $value->description,
                "imgPath" => 'uploads/category.png',
                "isActive"=> $value->isActive
            ]);
        }
    }
}
