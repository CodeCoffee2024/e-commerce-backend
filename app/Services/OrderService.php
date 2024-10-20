<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\Cart;
use App\Models\CityMunicipality;
use App\Models\Province;
use App\Models\ShippingMatrix;
use App\Models\Region;
use App\Models\Product;
use \Datetime;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class OrderService
{
    protected $user;

    public function __construct()
    {
        // Set the authenticated user
        $this->user = Auth::guard('sanctum')->user();
    }

    public function createOrder($data)
    {
        $cartItems = $data['cartItems'];
        $totalPrice = 0;
        $totalShipping = 0;
        $grandTotal = 0;
        $pickupIds = [];
        $shippingMerchants = [];  // To store the shipping costs per merchant
        $cart = Cart::where('user_id', $this->user->id)->first();
        $cartContents = array_filter(json_decode($cart->content), function($item) {
            return $item->isSelected;
        });
        $cartContentProductIds = array_map(function($item) {
            return $item->product->id;
        }, $cartContents);
        foreach ($cartItems as $cartItem ) {
            if (!in_array($cartItem['product']['id'], $cartContentProductIds)) {
                return response()->json([
                    'error' => "A product does exist in the selected cart items."
                ], 400);
            }
            $totalPrice = $cartItem['product']['price'] * $cartItem['quantity'];
            $product = Product::where('id', $cartItem['product']['id'])->first();
            if (!in_array($product->pickupAddress->id, $pickupIds)) {
                array_push($pickupIds, $product->pickupAddress->id);
                $shippingFee = ShippingMatrix::where('destination_cityMunicipality_id', $cartItem['product']['pickupAddress']['cityMunicipality']['id'])
                ->where('origin_cityMunicipality_id', $product->pickupAddress->barangay->cityMunicipality->id)->first();
                $totalShipping = $shippingFee != null ? $shippingFee->price : ShippingMatrix::defaultFee();
                $product->load('pickupAddress');
                $shippingMerchants[] = [
                    'merchant_address_id' => $product->pickupAddress->id,  // Assuming a merchant relationship
                    'shipping_cost' => $totalShipping
                ];
            }
        }
        $grandTotal = $totalShipping + $totalPrice;
        $referenceNumber = $this->generateUniqueReferenceNumber();
        $order = Order::create([
            'user_id' => $this->user->id,
            'cart' => json_encode($data['cartItems']),
            'reference_number' => $referenceNumber,
            'shipping_address_id' => $data['shippingAddress']['id'],
            'paymentOption' => $data['paymentOption'],
            'totalShipping' => $totalShipping,   // Store the total shipping
            'totalPrice' => $grandTotal,    // Store the grand total
            'status' => 'Pending'    // Store the grand total
        ]);

        $updatedCart = array_filter(json_decode($cart->content), function($item) {
            return !$item->isSelected;
        });
        
        $cart->update([
            'content' => json_encode($updatedCart)
        ]);

        foreach ($shippingMerchants as $merchant) {
            $order->shippingMerchants()->attach($merchant['merchant_address_id'], [
                'shipping_cost' => $merchant['shipping_cost']
            ]);
        }
        $email = $this->user->email;
        Mail::send('order.order_created', ['order' => $order], function ($message) use ($email, $order) {
            $message->to($email)->subject('Order # ' . $order->reference_number);  // Use reference_number here
        });
    
        return $order;
    }
    /**
     * Generate unique reference number
     *
     * @return string
     */
    private function generateUniqueReferenceNumber()
    {
        do {
            $referenceNumber = strtoupper(substr(md5(mt_rand()), 0, 6));
        } while (Order::where('reference_number', $referenceNumber)->exists());
    
        return $referenceNumber;
    }
}