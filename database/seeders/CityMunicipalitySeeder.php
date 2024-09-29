<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\CityMunicipality;
use Illuminate\Database\Seeder;

class CityMunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/cityMunicipality.json");
        $cityMunicipalities = json_decode($json);
        foreach ($cityMunicipalities as $key => $value) {
            DB::table('cityMunicipalities')->insert([
                "psgcCode" => $value->psgcCode,
                "description" => $value->description,
                "code" => $value->code,
                "regionCode" => $value->regionCode,
                "provincialCode" => $value->provincialCode,
                "isActive"=> 1
            ]);
        }
    }
}
