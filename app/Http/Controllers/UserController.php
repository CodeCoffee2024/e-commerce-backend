<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserLoginAdminRequest;
use App\Http\Requests\GoogleUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function loginViaGoogle(GoogleUserRequest $request)
    {
        $user = $this->userService->loginUserViaGoogle($request->validated());
        return response()->json(['user' => $user], 201);
    }

    public function login(UserLoginRequest $request)
    {
        $user = $this->userService->login($request->validated());
        return response()->json(['user' => $user], 201);
    }

    public function loginAdmin(UserLoginAdminRequest $request)
    {
        $user = $this->userService->loginAdmin($request->validated());
        return response()->json(['user' => $user], 201);
    }

    public function authAccess(Request $request)
    {
        return response()->json($request->user() ? true : false);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json(['user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $region)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $region)
    {
        //
    }
}
