<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Province;
use Illuminate\Support\Facades\File;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/province.json");
        $provinces = json_decode($json);
        foreach ($provinces as $key => $value) {
            Province::create([
                "psgcCode" => $value->psgcCode,
                "description" => $value->description,
                "regionCode" => $value->regionCode,
                "code" => $value->code,
                "isActive"=> 1
            ]);
        }
    }
}
