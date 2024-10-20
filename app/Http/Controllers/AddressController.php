<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\AddressService;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $addressService;
    protected $user;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
        $this->user = Auth::guard('sanctum')->user();
    }
    public function index(Request $request) {
        
        if ($this->user) {
            $token = $this->user->currentAccessToken(); 
            $user = LoginLog::where('user_id', $this->user->id)->first();
            if (Carbon::now()->greaterThan($user->expires_at)) {
                return response()->json(['error' => 'Token has expired'], 401);
            }

            $addressId = $request->query('id'); 
            $address = Address::with(['barangay.cityMunicipality', 'barangay.province', 'barangay.region'])
            ->where('isActive', true)
            ->findOrFail($addressId);
    
            return new AddressResource($address);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        $addresses = Address::where('addresses.isActive', true)->where('addresses.user_id', $this->user->id)        
        ->join('barangays', 'barangays.id', '=', 'addresses.barangay_id')
        ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
        ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
        ->join('regions', 'barangays.regionCode', '=', 'regions.code')
        ->select(
        'addresses.id',
        'addresses.zipCode',
        'addresses.isMainDeliveryAddress',
        'addresses.blockLotFloorBuildingName',
        'addresses.streetAddress',
        'barangays.id as barangay_id',
        'barangays.description as barangay_description',
        'city_municipalities.id as citymunicipality_id',
        'city_municipalities.description as citymunicipality_description',
        'provinces.id as province_id',
        'provinces.description as province_description',
        'regions.id as region_id',
        'regions.description as region_description'
        )->get();
        return AddressResource::collection($addresses);
    }

    public function defaultDeliveryAddress()
    {
        $address = Address::where('addresses.isActive', true)->where('addresses.user_id', $this->user->id)->orderBy('addresses.isMainDeliveryAddress', 'desc')
        ->join('barangays', 'barangays.id', '=', 'addresses.barangay_id')
        ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
        ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
        ->join('regions', 'barangays.regionCode', '=', 'regions.code')
        ->select(
        'addresses.id',
        'addresses.zipCode',
        'addresses.contactNumber',
        'addresses.isMainDeliveryAddress',
        'addresses.blockLotFloorBuildingName',
        'addresses.streetAddress',
        'barangays.id as barangay_id',
        'barangays.description as barangay_description',
        'city_municipalities.id as citymunicipality_id',
        'city_municipalities.description as citymunicipality_description',
        'provinces.id as province_id',
        'provinces.description as province_description',
        'regions.id as region_id',
        'regions.description as region_description'
        )->first();
        if ($address) {
            return new AddressResource($address);
        } else {
            return null;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = $this->addressService->createOrUpdateAddress($request->validated());
        return response()->json(['address' => $address], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function delete(Request $request)
    {
        $address = $this->addressService->delete($request);
        $addresses = Address::where('addresses.isActive', true)->where('addresses.user_id', $this->user->id)        
        ->join('barangays', 'barangays.id', '=', 'addresses.barangay_id')
        ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
        ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
        ->join('regions', 'barangays.regionCode', '=', 'regions.code')
        ->select(
        'addresses.id',
        'addresses.zipCode',
        'addresses.isMainDeliveryAddress',
        'addresses.blockLotFloorBuildingName',
        'addresses.streetAddress',
        'barangays.id as barangay_id',
        'barangays.description as barangay_description',
        'city_municipalities.id as citymunicipality_id',
        'city_municipalities.description as citymunicipality_description',
        'provinces.id as province_id',
        'provinces.description as province_description',
        'regions.id as region_id',
        'regions.description as region_description'
        )->get();
        return AddressResource::collection($addresses);
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        //
    }
}
