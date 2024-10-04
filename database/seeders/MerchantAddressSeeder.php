<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MerchantAddress;
use Illuminate\Support\Facades\File;

class MerchantAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/merchantAddress.json");
        $categories = json_decode($json);
        foreach ($categories as $key => $value) {
            MerchantAddress::create([
                "blockLotFloorBuildingName" => $value->blockLotFloorBuildingName,
                "streetAddress" => $value->streetAddress,
                "zipCode" => $value->zipCode,
                "barangay_id" => $value->barangay_id,
                "merchant_id" => $value->merchant_id,
                "isMainPickupAddress" => $value->isMainPickupAddress,
                "isMainReturnAddress" => $value->isMainReturnAddress,
                "isActive"=> 1
            ]);
        }
    }
}
