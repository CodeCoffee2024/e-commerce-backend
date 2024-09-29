<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Barangay;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/barangay.json");
        $barangays = json_decode($json);
        foreach ($barangays as $key => $value) {
            Barangay::create([
                "code" => $value->code,
                "description" => $value->description,
                "regionCode" => $value->regionCode,
                "provincialCode" => $value->provincialCode,
                "cityMunicipalityCode" => $value->cityMunicipalityCode,
                "isActive"=> 1
            ]);
        }
    }
}
