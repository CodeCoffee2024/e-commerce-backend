<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderFragment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;
    protected $user;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->user = Auth::guard('sanctum')->user();
    }
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
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());
        return response()->json($order, 201);
    }
    public function myOrders(Request $request) {
        
        $status = $request->query('status');
        $orders = Order::where('user_id', $this->user->id)
            ->select('orders.id as order_id', 'orders.*')
            ->whereHas('items', function ($query) use ($status) {
                if ($status && $status != 'All') {
                    $query->where('status', $status);
                }
            })
            ->orderBy('orders.created_at', 'desc')
            ->limit(20)
            ->get();

        if ($status && $status != 'All') {
            $orders->load(['items' => function ($query) use ($status) {
                $query->where('status', $status);
            }]);
        } else {
            $orders->load('items');
        }
    
        return OrderFragment::collection($orders);
    }
    public function list(Request $request) {
        
        $status = $request->input('status');
        $customers = $request->input('customers');
        $shipTos = $request->input('shipTos');
        $search = $request->input('search');
        $sort = $request->input('sortBy');
        $page = $request->input('page', 1);
        $orders = Order::with(['user', 'shippingAddress'])
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->join('addresses', 'addresses.id', '=', 'orders.shipping_address_id')
        ->join('barangays', 'barangays.id', '=', 'addresses.barangay_id')
        ->join('city_municipalities', 'city_municipalities.code', '=', 'barangays.cityMunicipalityCode')
        ->select('orders.id as order_id', 'orders.*'); // Alias orders.id to order_id

        if ($status && $status != 'All') {
            $orders->where('orders.status', $status);
        }
        if (!empty($customers)) {
            $customerIds = array_map(function ($customer) {
                return is_object($customer) ? $customer->id : $customer['id'];
            }, $customers);
            $orders->whereIn('users.id', $customerIds);
        }
        if (!empty($shipTos)) {
            $shipToIds = array_map(function ($shipTo) {
                return is_object($shipTo) ? $shipTo->id : $shipTo['id'];
            }, $shipTos);
            $orders->whereIn('city_municipalities.id', $shipToIds);
        }
        if (!empty($search)) {
            $orders->when($search, function ($query, $search) {
                return $query->where('orders.reference_number', 'like', '%' . $search . '%');
            });
        }
        $totalOrders = $orders->count();
        $totalPages = ceil($totalOrders / 20);
        
        if ($sort) {
            $direction = substr($sort, 0, 1) == '-' ? 'desc' : 'asc';
            $column = substr($sort, 1, strlen($sort));
            switch($column) {
                case 'customer':
                    $column = 'users.name';
                break;
                case 'shipTo':
                    $column = 'city_municipalities.description';
                    break;
                default:
                    $column = 'orders.created_at';
                    break;
            }
            $orders->orderBy($column, $direction);
        }
        $orders = $orders->offset(($page - 1) * 20) // Correct the offset based on the page
        ->limit(20)
        ->get();

        $result = [
            'result' => OrderFragment::collection($orders),
            'page' => $page,
            'pageCount' => $totalPages
        ];
        return $result;
    }
    public function listCustomer(Request $request) {
        $customer = $request->input('customer');
        $customers = $request->input('exclude');
        $orders = Order::with(['user']);
        $orders = $orders->orderBy('users.name', 'asc')
        ->join('users', 'users.id', '=', 'orders.user_id')
        ->when($customer, function ($query, $customer) {
            return $query->where('users.name', 'like', '%' . $customer . '%');
        })
        ->when(!empty($customers), function ($query) use ($customers) {
            return $query->whereNotIn('users.id', $customers); // Filter out specific customer IDs
        })
        ->select('users.name', 'users.id') // Assuming these are the correct column names
        ->distinct('users.id','users.name')
        ->limit(20)
        ->get();

        $result = [
            'result' => $orders
        ];
        return $result;
    }
    public function listShipTo(Request $request) {
        $shipTo = $request->input('shipTo');
        $exclude = $request->input('exclude');
        $include = $request->input('include');
        $orders = Order::query()
            ->join('addresses', 'addresses.id', '=', 'orders.shipping_address_id')
            ->join('barangays', 'barangays.id', '=', 'addresses.barangay_id')
            ->join('city_municipalities', 'city_municipalities.code', '=', 'barangays.cityMunicipalityCode') // Corrected the join condition
            ->when($shipTo, fn($query) => $query->where('city_municipalities.description', 'like', '%' . $shipTo . '%')) // Simplified with arrow function
            ->when(!empty($exclude), fn($query) => $query->whereNotIn('city_municipalities.id', $exclude)) // Apply exclusion if not empty
            ->when(!empty($include), fn($query) => $query->whereIn('city_municipalities.id', $include)) // Apply exclusion if not empty
            ->select('city_municipalities.description', 'city_municipalities.id') // Select necessary fields
            ->distinct() // `distinct` works with single argument in Laravel
            ->orderBy('city_municipalities.description', 'asc') // Moved orderBy after joins
            ->limit(20) // Limit to 20 results
            ->get();
    
        return ['result' => $orders];
    }

    public function setAsToShip(Request $request) {
        $orderIds = $request->input('items');
        $orderId = $request->input('id');
        $order = Order::with(['user', 'shippingAddress.barangay.cityMunicipality', 'shippingMerchants', 'items'])
            ->select('orders.id as order_id', 'orders.*')
            ->find($orderId);
        
        $this->orderService->setOrderStatus($order, Order::STATUS_TO_SHIP, $orderIds, 'canSetAsToShip');
        $order = Order::with(['user', 'shippingAddress.barangay.cityMunicipality', 'shippingMerchants', 'items'])
            ->select('orders.id as order_id', 'orders.*')
            ->find($orderId);
        return new OrderFragment($order);
    }

    public function setAsForDelivery(Request $request) {
        $orderIds = $request->input('items');
        $orderId = $request->input('id');
        $order = Order::with(['user', 'shippingAddress.barangay.cityMunicipality', 'shippingMerchants', 'items'])
        ->select('orders.id as order_id', 'orders.*')
            ->find($orderId);
        $this->orderService->setOrderStatus($order, Order::STATUS_FOR_DELIVERY, $orderIds, 'canSetAsForDelivery');
        $order = Order::with(['user', 'shippingAddress.barangay.cityMunicipality', 'shippingMerchants', 'items'])
            ->select('orders.id as order_id', 'orders.*')
            ->find($orderId);
        return new OrderFragment($order);
    }

    public function setAsReceived(Request $request) {
        $orderItemId = $request->input('id');
        $orderItem = OrderItem::with(['order'])
        ->find($orderItemId);
        $this->orderService->setOrderItemStatus($orderItem, Order::STATUS_RECEIVED, 'canSetAsReceived');
        return true;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        if ($id == null) {
            return response()->json([
                'error' => 'Order ID is required.'
            ], 400);
        }
        $query = Order::with(['user', 'shippingAddress.barangay.cityMunicipality', 'shippingMerchants'])
            ->select('orders.id as order_id', 'orders.*')
            ->where('orders.id', $id);
        
        $status = $request->query('status');
        if ($status && $status != 'All') {
            $query->where('status', $status);
        }
        
        // Fetch the order
        $order = $query->first();
        if ($order == null) {
            return null;
        }
        return new OrderFragment($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
