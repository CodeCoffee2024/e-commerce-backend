<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Barangay;
use App\Models\Province;
use App\Http\Requests\StoreRegionRequest;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use Carbon\Carbon;
use App\Http\Resources\RegionFragment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateRegionRequest;

class RegionController extends Controller
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
            $province = $request->query('province');
            $region = $request->query('region');
            $regions = Barangay::where('barangays.isActive', true)
                ->where('city_municipalities.isActive', true)
                ->where('provinces.isActive', true)
                ->where('regions.isActive', true)
                ->when($barangay, function ($query, $barangay) {
                    return $query->where('barangays.description', 'like', '%' . $barangay . '%');
                })
                ->when($cityMunicipality, function ($query, $cityMunicipality) {
                    return $query->where('city_municipalities.description', 'like', '%' . $cityMunicipality . '%');
                })
                ->when($province, function ($query, $province) {
                    return $query->where('provinces.description', 'like', '%' . $province . '%');
                })
                ->when($region, function ($query, $region) {
                    return $query->where('regions.description', 'like', '%' . $region . '%');
                })
                ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
                ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
                ->join('regions', 'barangays.regionCode', '=', 'regions.code')
                ->select('regions.id', 'regions.description')
                ->get();
            return RegionFragment::collection($regions);
        }
    }

    public function all() {
        $regions = Region::where('isActive', true)->get();
        return RegionFragment::collection($regions);
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
    public function store(StoreRegionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        //
    }
}
