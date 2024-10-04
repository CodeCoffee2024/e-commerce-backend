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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddressService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('sanctum')->user();
    }
    public function delete($data) {
        $id = $data['id'];
        $address = Address::where('user_id', $this->user->id)
        ->where('id', $id)
        ->first();
        if ($address) {
            // dump("SS");
            $address->delete();
        } else {
            throw new ModelNotFoundException('Address not found or does not belong to the current user.');
        }
    }
    public function createOrUpdateAddress($data)
    {
        $barangayName = $data['barangay']['description'];
        $cityMunicipalityName = $data['cityMunicipality']['description'];
        $provinceName = $data['province']['description'];
        $regionName = $data['region']['description'];
        $barangay = DB::table('barangays')
            ->where('barangays.isActive', true)
            ->where('city_municipalities.isActive', true)
            ->where('provinces.isActive', true)
            ->where('regions.isActive', true)
            ->when($barangayName, function ($query) use ($barangayName) {
                return $query->where('barangays.description', '=', $barangayName);
            })
            ->when($cityMunicipalityName, function ($query) use ($cityMunicipalityName) {
                return $query->where('city_municipalities.description', '=', $cityMunicipalityName);
            })
            ->when($provinceName, function ($query) use ($provinceName) {
                return $query->where('provinces.description', '=', $provinceName);
            })
            ->when($regionName, function ($query) use ($regionName) {
                return $query->where('regions.description', '=', $regionName);
            })
            ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
            ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
            ->join('regions', 'barangays.regionCode', '=', 'regions.code')
            ->select(
                'barangays.description as barangayName', 'barangays.id as barangayId',
                'city_municipalities.description as cityMunicipalityName', 'city_municipalities.id as cityMunicipalityId',
                'provinces.description as provinceName', 'provinces.id as provinceId',
                'regions.description as regionName', 'regions.id as regionId'
            )
            ->first();
            
        if ($barangay) {
            if ($data['isDefaultDeliveryAddress']) {
                Address::where('user_id', $this->user->id)
                    ->where('isMainDeliveryAddress', true)
                    ->update(['isMainDeliveryAddress' => false]);
            }
            if (!isset($data['id'])) {
                $address = Address::create([
                    'blockLotFloorBuildingName' => $data['blockLotFloorBuildingName'],
                    'streetAddress' => $data['streetAddress'],
                    'zipCode' => $data['zipCode'],
                    'isMainDeliveryAddress' => $data['isDefaultDeliveryAddress'],
                    'barangay_id' => $barangay->barangayId,
                    'isActive' => true
                ]);
            } else {
                $address = Address::findOrFail($data['id']);
                $address->update([
                    'blockLotFloorBuildingName' => $data['blockLotFloorBuildingName'],
                    'streetAddress' => $data['streetAddress'],
                    'zipCode' => $data['zipCode'],
                    'isMainDeliveryAddress' => $data['isDefaultDeliveryAddress'],
                    'barangay_id' => $barangay->barangayId,
                    'isActive' => true
                ]);
            }
            $this->user->addresses()->save($address);
            return $address;
        }
    }
}