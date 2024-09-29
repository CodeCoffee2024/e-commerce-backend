<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use Illuminate\Support\Facades\File;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/region.json");
        $regions = json_decode($json);
        foreach ($regions as $key => $value) {
            Region::create([
                "psgcCode" => $value->psgcCode,
                "description" => $value->description,
                "code" => $value->code,
                "isActive"=> 1
            ]);
        }
    }
}
