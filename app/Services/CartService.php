<?php
namespace App\Services;

use App\Models\Cart;
use \Datetime;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected $user;

    public function __construct()
    {
        // Set the authenticated user
        $this->user = Auth::guard('sanctum')->user();
    }
    public function createOrUpdateCartItem($data)
    {
        $cart = Cart::where('user_id', $this->user->id)->first();
        if (!$cart) {
            $cart = [[
                'product' => $data['product'],
                'isSelected' => $data['isSelected'] ?? false,
                'quantity' => $data['quantity'],
                'datetime' => new DateTime()
            ]];
            $cart = Cart::create([
                'user_id' => $this->user->id,
                'content' => json_encode($cart)
            ]);
        }
        $content = collect(json_decode($cart->content, true));
        $isExisting = $content->firstWhere('product.id', $data['product']['id']);
        if (!$isExisting) {
            $cartItem = [
                'product' => $data['product'],
                'isSelected' =>  $data['isSelected'] ?? false,
                'quantity' => $data['quantity'],
                'datetime' => new DateTime()
            ];
            $content->push($cartItem);
            $cart->update([
                'content' => json_encode($content)
            ]);
        } else {
            $content = $content->map(function ($item) use ($data) {
                if ($item['product']['id'] === $data['product']['id']) {
                    // Update quantity or other fields as needed
                    $item['quantity'] = $data['quantity'];
                    $item['isSelected'] =  $data['isSelected'] ?? false;
                }
                return $item;
            });
            $cart->update([
                'content' => json_encode($content)
            ]);
        }
        return Cart::where('user_id', $this->user->id)->select('content as cart')->first();
    }
}