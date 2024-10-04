<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProvinceFragment;
use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;

class ProvinceController extends Controller
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
            $provinces = Barangay::where('barangays.isActive', true)
                ->where('city_municipalities.isActive', true)
                ->where('provinces.isActive', true)
                ->when($barangay, function ($query, $barangay) {
                    return $query->where('barangays.description', 'like', '%' . $barangay . '%');
                })
                ->when($cityMunicipality, function ($query, $cityMunicipality) {
                    return $query->where('city_municipalities.description', 'like', '%' . $cityMunicipality . '%');
                })
                ->when($province, function ($query, $province) {
                    return $query->where('provinces.description', 'like', '%' . $province . '%');
                })
                ->join('city_municipalities', 'barangays.cityMunicipalityCode', '=', 'city_municipalities.code')
                ->join('provinces', 'barangays.provincialCode', '=', 'provinces.code')
                ->select('provinces.id', 'provinces.description')
                ->get();
            return ProvinceFragment::collection($provinces);
        }
    }
    
    public function all(Request $request) {
        $region = $request->query('region');
        $provinces = Province::where('provinces.isActive', true)
        ->where('regions.id', '=', $region)
        ->join('regions', 'regions.code', '=', 'provinces.regionCode')
        ->select('provinces.id', 'provinces.description')
        ->get();
        return ProvinceFragment::collection($provinces);
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
    public function store(StoreProvinceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        //
    }
}
