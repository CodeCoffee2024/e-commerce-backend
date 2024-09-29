<?php
namespace App\Services;

use App\Models\Address;
use App\Models\Barangay;
use App\Models\CityMunicipality;
use App\Models\Province;
use App\Models\Region;
use \Datetime;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('sanctum')->user();
    }
    public function createOrUpdateAddress($data)
    {
        $barangayName = $data['barangay']['description'];
        $cityMunicipalityName = $data['cityMunicipality']['description'];
        $provinceName = $data['province']['description'];
        $regionName = $data['region']['description'];
        $barangay = Barangay::where('barangays.isActive', true)
            ->where('citymunicipalities.isActive', true)
            ->where('provinces.isActive', true)
            ->where('regions.isActive', true)
            ->when($barangayName, function ($query, $barangayName) {
                return $query->where('barangays.description', 'like', '%' . $barangayName . '%');
            })
            ->when($cityMunicipalityName, function ($query, $cityMunicipalityName) {
                return $query->where('citymunicipalities.description', 'like', '%' . $cityMunicipalityName . '%');
            })
            ->when($provinceName, function ($query, $provinceName) {
                return $query->where('provinces.description', 'like', '%' . $provinceName . '%');
            })
            ->when($regionName, function ($query, $regionName) {
                return $query->where('regions.description', 'like', '%' . $regionName . '%');
            })
            ->join('citymunicipalities', 'barangays.cityMunicipalityCode', '=', 'citymunicipalities.code')
            ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
            ->join('regions', 'barangays.regionCode', '=', 'regions.code')
            ->first();  // Get the first match
        if ($barangay) {
            $address = Address::create([
                'blockLotFloorBuildingName' => $data['blockLotFloorBuildingName'],
                'streetAddress' => $data['streetAddress'],
                'zipCode' => $data['zipCode'],
                'isMainDeliveryAddress' => $data['isDefaultDeliveryAddress'],
                'isMainReturnAddress' => $data['isDefaultReturnAddress'],
                'barangay_id' => $barangay->id,
                'isActive' => true
            ]);
            $this->user->addresses()->save($address);
            return $address;
        }
    }
}