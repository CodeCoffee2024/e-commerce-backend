<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([MerchantSeeder::class]);
        $this->call([CategorySeeder::class]);
        $this->call([RegionSeeder::class]);
        $this->call([ProvinceSeeder::class]);
        $this->call([CityMunicipalitySeeder::class]);
        $this->call([BarangaySeeder::class]);
        $this->call([AddressSeeder::class]);
        $this->call([MerchantAddressSeeder::class]);
        $this->call([ShippingMatrixSeeder::class]);
        $this->call([ProductSeeder::class]);
    }
}
