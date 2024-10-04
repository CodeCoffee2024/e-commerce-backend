<?php

namespace App\Http\Controllers;

use App\Models\CityMunicipality;
use App\Http\Resources\CityMunicipalityFragment;
use App\Http\Requests\StoreCityMunicipalityRequest;
use App\Http\Requests\UpdateCityMunicipalityRequest;
use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CityMunicipalityController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('sanctum')->user();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($this->user) {
            $token = $this->user->currentAccessToken(); 
            $user = LoginLog::where('user_id', $this->user->id)->first();
            if (Carbon::now()->greaterThan($user->expires_at)) {
                return response()->json(['error' => 'Token has expired'], 401);
            }
            $barangay = $request->query('barangay');
            $cityMunicipality = $request->query('cityMunicipality');
            $cityMunicipalities = Barangay::where('barangays.isActive', true)->where('city_municipalities.isActive', true)
                ->when($barangay, function ($query, $barangay) {
                    return $query->where('barangays.description', 'like', '%' . $barangay . '%');
                })
                ->when($cityMunicipality, function ($query, $cityMunicipality) {
                    return $query->where('city_municipalities.description', 'like', '%' . $cityMunicipality . '%');
                })
                ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code') // Join on the cityMunicipalityCode
                ->select('city_municipalities.id', 'city_municipalities.description')
                ->get();
            return CityMunicipalityFragment::collection($cityMunicipalities);
        }
    }

    public function all(Request $request) {
        $region = $request->query('region');
        $province = $request->query('province');
        $cityMunicipalities = CityMunicipality::where('city_municipalities.isActive', true)
        ->where('regions.id', '=', $region)
        ->where('provinces.id', '=', $province)
        ->join('provinces', 'provinces.code', '=', 'city_municipalities.provincialCode')
        ->join('regions', 'regions.code', '=', 'provinces.regionCode')
        ->select('city_municipalities.id', 'city_municipalities.description')
        ->get();
        return CityMunicipalityFragment::collection($cityMunicipalities);
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
    public function store(StoreCityMunicipalityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CityMunicipality $cityMunicipality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CityMunicipality $cityMunicipality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityMunicipalityRequest $request, CityMunicipality $cityMunicipality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CityMunicipality $cityMunicipality)
    {
        //
    }
}
