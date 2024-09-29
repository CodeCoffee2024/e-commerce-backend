<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function getAllProducts(Request $request) {
        $products = Product::with(['category', 'merchant'])->get();
        $productList = [];
        foreach ($products as $product) {
            $productList[] = [
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
            ];
        }

        return response()->json($productList);
    }
}