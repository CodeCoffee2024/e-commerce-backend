<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        $orders = Order::where('user_id', $this->user->id);

        if ($status) {
            $orders->where('orders.status', $status);
        }
        
        $orders = $orders->orderBy('orders.created_at', 'desc')
                              ->limit(20)
                              ->get();
        return OrderFragment::collection($orders);
    }
    public function list(Request $request) {
        
        $status = $request->query('status');
        $customer = $request->query('customer');
        $search = $request->query('search');
        $page = $request->query('page', 1); // Default to page 1 if not provided
        $orders = Order::with(['user', 'shippingAddress']);

        if ($status) {
            $orders->where('orders.status', $status);
        }
        $totalOrders = $orders->count();
        $totalPages = ceil($totalOrders / 20);
        
        $orders = $orders->orderBy('orders.created_at', 'desc')
        ->offset(($page - 1) * 20) // Correct the offset based on the page
        ->limit(20)
        ->get();

        $result = [
            'result' => OrderFragment::collection($orders),
            'page' => $page,
            'pageCount' => $totalPages
        ];
        return $result;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
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
