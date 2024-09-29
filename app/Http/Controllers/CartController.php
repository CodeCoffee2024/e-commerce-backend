<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\LoginLog;
use App\Http\Requests\StorecartRequest;
use App\Http\Requests\UpdatecartRequest;
use App\Services\CartService;
use App\Http\Requests\UpdateCartItemRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    protected $cartService;
    protected $user;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->user = Auth::guard('sanctum')->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user) {
            $token = $this->user->currentAccessToken(); 
            $user = LoginLog::where('user_id', $this->user->id)->first();
            if (Carbon::now()->greaterThan($user->expires_at)) {
                return response()->json(['error' => 'Token has expired'], 401);
            }
            $cart = Cart::where('user_id', $this->user->id)->firstOrFail();
            return response()->json($cart->content);
        }
        return response()->json(false);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = $request->input('user');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCart(StorecartRequest $request)
    {
        if ($this->user) {
            $token = $this->user->currentAccessToken(); 
            $user = LoginLog::where('user_id', $this->user->id)->first();
            if (Carbon::now()->greaterThan($user->expires_at)) {
                return response()->json(['error' => 'Token has expired'], 401);
            }
            $cart = Cart::where('user_id', $this->user->id)->first();
            $cart->update([
                'content' => $request->input('content')
            ]);
            return response()->json($request->input('content'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorecartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createOrUpdateCartItem(StorecartRequest $request)
    {
        $userId = $request->input('user');
        
        $cart = $this->cartService->createOrUpdateCartItem($request->validated());
        return response()->json($cart);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatecartRequest  $request
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecartRequest $request, cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(cart $cart)
    {
        //
    }
}
