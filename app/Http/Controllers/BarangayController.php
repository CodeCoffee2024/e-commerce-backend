<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Http\Requests\StoreBarangayRequest;
use App\Http\Requests\UpdateBarangayRequest;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\BarangayFragment;
use Illuminate\Support\Facades\Auth;

class BarangayController extends Controller
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
            $barangays = Barangay::where('isActive', true)
            ->when($barangay, function ($query, $barangay) {
                return $query->where('description', 'like', '%' . $barangay . '%');
            })
            ->select('id', 'description')
            ->orderBy('description', 'asc') // Order alphabetically by description
            ->limit(20) // Apply the limit correctly before fetching
            ->get();
    
            return BarangayFragment::collection($barangays);
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
    public function store(StoreBarangayRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Barangay $barangay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barangay $barangay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarangayRequest $request, Barangay $barangay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barangay $barangay)
    {
        //
    }
}
