<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->productService->getAllProducts($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['merchant', 'category'])->findOrFail($id);
        return response()->json([
            'productName' => $product->name,
            'productDescription' => $product->description,
            'productImgPath' => $product->imgPath,
            'id' => $product->id,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'categoryId' => $product->category->id,
            'isActive' => $product->isActive,
            'category' => $product->category,
            'merchant' => $product->merchant,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
