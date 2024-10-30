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
use Illuminate\Support\Facades\Gate;
use App\Models\OrderItem;
use App\Models\OrderLog;
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
            $totalPrice += $cartItem['product']['price'] * $cartItem['quantity'];
            $product = Product::where('id', $cartItem['product']['id'])->first();
            if (!in_array($product->pickupAddress->id, $pickupIds)) {
                array_push($pickupIds, $product->pickupAddress->id);
                $shippingFee = ShippingMatrix::where('destination_cityMunicipality_id', $cartItem['product']['pickupAddress']['cityMunicipality']['id'])
                ->where('origin_cityMunicipality_id', $product->pickupAddress->barangay->cityMunicipality->id)->first();
                $totalShipping += $shippingFee != null ? $shippingFee->price : ShippingMatrix::defaultFee();
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
            'reference_number' => $referenceNumber,
            'shipping_address_id' => $data['shippingAddress']['id'],
            'paymentOption' => $data['paymentOption'],
            'totalShipping' => $totalShipping,
            'totalPrice' => $grandTotal,
            'status' => Order::STATUS_PENDING 
        ]);
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem['product']['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cartItem['quantity'],
                'price' => $cartItem['product']['price'],
                'total_price' => $cartItem['product']['price'] * $cartItem['quantity'],
                'status' => Order::STATUS_PENDING
            ]);
        }

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
        $email = $order->user->email;
        Mail::send('order.order_created', ['order' => $order], function ($message) use ($email, $order) {
            $message->to($email)->subject('Order # ' . $order->reference_number);  // Use reference_number here
        });
    
        return $order;
    }
    public function setOrderStatus(Order $order, $status, $orderItemIds, $permissionItems) {
        $orderItems = $order->items()->whereIn('id', $orderItemIds)->get();
        $subTotal = 0;
        foreach ($orderItems as $item) {
            Gate::authorize($permissionItems, $item);  // Authorize on OrderItem
            $subTotal += $item->price * $item->quantity;
            $item->status = $status;
            $item->save();
            OrderLog::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'status' => $status
            ]);
        }
        $statusCount = $order->items()->where('status', $status)->count();
        if ($statusCount == $order->items()->count()) {
            $order->update([
                'status' => $status
            ]);
        }
        $order->save();
        $orderLogs = OrderLog::where('order_id',  $order->id)
        ->orderBy('created_at', 'asc')
        ->get();
        $email = $order->user->email;
        Mail::send('order.order_status', ['order' => $order, 'subTotal' => $subTotal, 'items' => $orderItems, 'logs' => $orderLogs, 'statusDescription' => Order::STATUS_DESCRIPTION], function ($message) use ($email, $order) {
            $message->to($email)->subject('Order # ' . $order->reference_number);  // Use reference_number here
        });
    }
    public function setOrderItemStatus(OrderItem $orderItem, $status, $permissionItems) {
        $subTotal = 0;
        Gate::authorize($permissionItems, $orderItem);  // Authorize on OrderItem
        $subTotal += $orderItem->price * $orderItem->quantity;
        $orderItem->status = $status;
        $orderItem->save();
        $statusCount = $orderItem->order->items->where('status', $status)->count();
        if ($statusCount == $orderItem->order->items->count()) {
            $orderItem->order->update([
                'status' => $status
            ]);
        }
        OrderLog::create([
            'order_id' => $orderItem->order->id,
            'order_item_id' => $orderItem->id,
            'status' => $status
        ]);
        $orderItem->order->save();
        $orderLogs = OrderLog::where('order_id',  $orderItem->order->id)
        ->orderBy('created_at', 'asc')
        ->get();
        $email = $orderItem->order->user->email;
        $order = $orderItem->order;
        Mail::send('order.order_status', ['order' => $order, 'subTotal' => $subTotal, 'items' => [$orderItem], 'logs' => $orderLogs, 'statusDescription' => Order::STATUS_DESCRIPTION], function ($message) use ($email, $order) {
            $message->to($email)->subject('Order # ' . $order->reference_number);  // Use reference_number here
        });
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