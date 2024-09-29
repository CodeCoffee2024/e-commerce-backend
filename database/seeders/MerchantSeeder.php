<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merchant;
use Illuminate\Support\Facades\File;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/merchant.json");
        $categories = json_decode($json);
        foreach ($categories as $key => $value) {
            Merchant::create([
                "name" => $value->name,
                "description" => $value->description,
                "isActive"=> $value->isActive,
                "imgPath" => 'uploads/merchant.png',
            ]);
        }
    }
}
