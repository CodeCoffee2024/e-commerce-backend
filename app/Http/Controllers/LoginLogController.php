<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Http\Requests\StoreLoginLogRequest;
use App\Http\Requests\UpdateLoginLogRequest;

class LoginLogController extends Controller
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
     * @param  \App\Http\Requests\StoreLoginLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLoginLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoginLog  $loginLog
     * @return \Illuminate\Http\Response
     */
    public function show(LoginLog $loginLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoginLog  $loginLog
     * @return \Illuminate\Http\Response
     */
    public function edit(LoginLog $loginLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLoginLogRequest  $request
     * @param  \App\Models\LoginLog  $loginLog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLoginLogRequest $request, LoginLog $loginLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoginLog  $loginLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoginLog $loginLog)
    {
        //
    }
}
