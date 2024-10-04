<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ShippingMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/shippingMatrix.json");
        $cityMunicipalities = json_decode($json);
        foreach ($cityMunicipalities as $key => $value) {
            DB::table('shipping_matrices')->insert([
                "origin_cityMunicipality_id" => $value->origin_cityMunicipality_id,
                "destination_cityMunicipality_id" => $value->destination_cityMunicipality_id,
                "fee" => $value->fee
            ]);
        }
    }
}
