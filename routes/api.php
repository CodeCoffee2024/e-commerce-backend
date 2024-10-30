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
use App\Http\Controllers\OrderController;
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
    Route::get('/address/getAll', [AddressController::class, 'getAll']);
    Route::get('/address', [AddressController::class, 'index']);
    Route::get('order/my-orders', [OrderController::class, 'myOrders']);
    Route::post('order/list', [OrderController::class, 'list']);
    Route::post('/order/create', [OrderController::class, 'store']);
    Route::post('/order/customerList', [OrderController::class, 'listCustomer']);
    Route::post('/order/shipToList', [OrderController::class, 'listShipTo']);
    Route::put('/order/setAsToShip', [OrderController::class, 'setAsToShip']);
    Route::put('/order/setAsForDelivery', [OrderController::class, 'setAsForDelivery']);
    Route::put('/order/receive-item', [OrderController::class, 'setAsReceived']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::delete('/address', [AddressController::class, 'delete']);
    Route::get('/address/defaultDeliveryAddress', [AddressController::class, 'defaultDeliveryAddress']);
});
Route::group([], function() {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/region/all', [RegionController::class, 'all']);
    Route::get('/region/search', [RegionController::class, 'search']);
    Route::get('/province/all', [ProvinceController::class, 'all']);
    Route::get('/cityMunicipality/all', [CityMunicipalityController::class, 'all']);
    Route::get('/product/{id}/{location}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/user/create', [UserController::class, 'store']);
    Route::post('/user/loginViaGoogle', [UserController::class, 'loginViaGoogle']);
    Route::post('/user/login', [UserController::class, 'login']);
    Route::post('/user/login-admin', [UserController::class, 'loginAdmin']);
});
