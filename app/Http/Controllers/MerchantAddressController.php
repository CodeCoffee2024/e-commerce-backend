<?php

namespace App\Http\Controllers;

use App\Models\MerchantAddress;
use App\Http\Requests\StoreMerchantAddressRequest;
use App\Http\Requests\UpdateMerchantAddressRequest;

class MerchantAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMerchantAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMerchantAddressRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MerchantAddress  $merchantAddress
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantAddress $merchantAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MerchantAddress  $merchantAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantAddress $merchantAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMerchantAddressRequest  $request
     * @param  \App\Models\MerchantAddress  $merchantAddress
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMerchantAddressRequest $request, MerchantAddress $merchantAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MerchantAddress  $merchantAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantAddress $merchantAddress)
    {
        //
    }
}
