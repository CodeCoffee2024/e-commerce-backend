<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function getAllCategories(Request $request) {
        
        // $products = Category::query()
        // ->select('category.name','product.name','product.quantity','product.price');
        
        // $productList = [];
        // foreach ($products as $value) {
        //     $productList[] = [
        //         'name' => $value->name,
        //         'price' => $value->price,
        //         'quantity' => $value->quantity,
        //     ];
        // }
        // return response()->json($productList);
        $products = Category::all();
        return response()->json($products);
    }
}