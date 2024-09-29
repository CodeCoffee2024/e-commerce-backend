<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityMunicipalityController;
use App\Http\Controllers\BarangayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user/checkAccess', [UserController::class, 'authAccess']);
// Route::middleware('auth:sanctum')->get('/login', [UserController::class, 'authAccess']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/createOrUpdateCartItem', [CartController::class, 'createOrUpdateCartItem']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::patch('/cart/updateCart', [CartController::class, 'updateCart']);
    Route::get('/address/regions', [RegionController::class, 'index']);
    Route::get('/address/provinces', [ProvinceController::class, 'index']);
    Route::get('/address/cityMunicipalities', [CityMunicipalityController::class, 'index']);
    Route::get('/address/barangays', [BarangayController::class, 'index']);
    Route::post('/address/createOrUpdate', [AddressController::class, 'store']);
    Route::post('/address', [AddressController::class, 'index']);
});
Route::group([], function() {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/user/create', [UserController::class, 'store']);
    Route::post('/user/loginViaGoogle', [UserController::class, 'loginViaGoogle']);
    Route::post('/user/login', [UserController::class, 'login']);
});
