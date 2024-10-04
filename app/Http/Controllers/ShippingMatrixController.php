<?php

namespace App\Http\Controllers;

use App\Models\ShippingMatrix;
use App\Http\Requests\StoreShippingMatrixRequest;
use App\Http\Requests\UpdateShippingMatrixRequest;

class ShippingMatrixController extends Controller
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
     * @param  \App\Http\Requests\StoreShippingMatrixRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShippingMatrixRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShippingMatrix  $shippingMatrix
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingMatrix $shippingMatrix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingMatrix  $shippingMatrix
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingMatrix $shippingMatrix)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShippingMatrixRequest  $request
     * @param  \App\Models\ShippingMatrix  $shippingMatrix
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShippingMatrixRequest $request, ShippingMatrix $shippingMatrix)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingMatrix  $shippingMatrix
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingMatrix $shippingMatrix)
    {
        //
    }
}
