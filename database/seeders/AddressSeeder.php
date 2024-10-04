<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Address;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get("database/data/address.json");
        $addresses = json_decode($json);
        foreach ($addresses as $key => $value) {
            Address::create([
                "blockLotFloorBuildingName" => $value->blockLotFloorBuildingName,
                "streetAddress" => $value->streetAddress,
                "zipCode" => $value->zipCode,
                "barangay_id" => $value->barangay_id,
                "user_id" => $value->user_id,
                "isMainDeliveryAddress" => $value->isMainDeliveryAddress,
                "isActive"=> 1
            ]);
        }
    }
}
