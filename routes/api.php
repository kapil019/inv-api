<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\SolariumController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\WarehouseController;
use \App\Http\Controllers\Api\CustomerController;
use \App\Http\Controllers\Api\CompanyController;
use \App\Http\Controllers\Api\CategoryController;
use \App\Http\Controllers\Api\AttributeController;
use \App\Http\Controllers\Api\AttributeValueController;
use \App\Http\Controllers\Api\CargoController;
use \App\Http\Controllers\Api\PackingController;
use \App\Http\Controllers\Api\Order\OrderController;
use \App\Http\Controllers\Api\Order\OrderItemController;

use \App\Http\Controllers\Api\ZoneController;
use \App\Http\Controllers\Api\SettingController;
use \App\Http\Controllers\Api\ZonePriceRuleController;

use \App\Http\Controllers\Api\PaymentController;

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

Route::post('/login', [AuthController::class, 'login']);


// Route::group(['prefix' => 'v1'], function () {
//     Route::middleware([
//         config('jetstream.auth_session'),
//     ])->group(function () {

        Route::get('/solr/ping', [SolariumController::class, 'ping']);
        Route::get('/solr/search', [SolariumController::class, 'search']);

        Route::get('/settings', [SettingController::class, 'getAll']);

        // Category
        Route::get('category', [CategoryController::class, 'getAll']);
        Route::post('category', [CategoryController::class, 'create']);
        Route::put('category/{id}', [CategoryController::class, 'update']);
        Route::delete('category/{id}', [CategoryController::class, 'delete']);

        // Attribute/value
        Route::get('attribute/value', [AttributeValueController::class, 'getAll']);
        Route::post('attribute/value', [AttributeValueController::class, 'create']);
        Route::put('attribute/value/{id}', [AttributeValueController::class, 'update']);
        Route::delete('attribute/value/{id}', [AttributeValueController::class, 'delete']);
        
        // Attributes
        Route::get('attribute', [AttributeController::class, 'getAll']);
        Route::post('attribute', [AttributeController::class, 'create']);
        Route::put('attribute/{id}', [AttributeController::class, 'update']);
        Route::delete('attribute/{id}', [AttributeController::class, 'delete']);

        // Product
        Route::get('products', [ProductController::class, 'getAll']);
        Route::get('product/{id}', [ProductController::class, 'get']);
        Route::post('product', [ProductController::class, 'create']);
        Route::put('product/{id}', [ProductController::class, 'update']);
        Route::delete('product/{id}', [ProductController::class, 'delete']);

        // Company
        Route::get('company', [CompanyController::class, 'getAll']);
        Route::post('company', [CompanyController::class, 'create']);
        Route::put('company/{id}', [CompanyController::class, 'update']);
        Route::delete('company/{id}', [CompanyController::class, 'delete']);

        // Warehouse
        Route::get('warehouse', [WarehouseController::class, 'getAll']);
        Route::post('warehouse', [WarehouseController::class, 'create']);
        Route::put('warehouse/{id}', [WarehouseController::class, 'update']);
        Route::delete('warehouse/{id}', [WarehouseController::class, 'delete']);

        // Customer
        Route::get('customer', [CustomerController::class, 'getAll']);
        Route::post('customer', [CustomerController::class, 'create']);
        Route::put('customer/{id}', [CustomerController::class, 'update']);
        Route::delete('customer/{id}', [CustomerController::class, 'delete']);
        
        // Cargo
        Route::get('cargo', [CargoController::class, 'getAll']);
        Route::post('cargo', [CargoController::class, 'create']);
        Route::put('cargo/{id}', [CargoController::class, 'update']);
        Route::delete('cargo/{id}', [CargoController::class, 'delete']);

        // Packing
        Route::get('packing', [PackingController::class, 'getAll']);
        Route::post('packing', [PackingController::class, 'create']);
        Route::put('packing/{id}', [PackingController::class, 'update']);
        Route::delete('packing/{id}', [PackingController::class, 'delete']);

        // Order/item
        Route::get('order/item/{type}', [OrderItemController::class, 'getAll']);
        Route::post('order/item/{type}', [OrderItemController::class, 'create']);
        Route::put('order/item/{type}/{id}', [OrderItemController::class, 'update']);
        Route::delete('order/item/{type}/{id}', [OrderItemController::class, 'delete']);

        // Order
        Route::get('order/{type}', [OrderController::class, 'getAll']);
        Route::get('order/{type}/{id}', [OrderController::class, 'get']);
        Route::post('order/{type}', [OrderController::class, 'create']);
        Route::put('order/{type}/{id}', [OrderController::class, 'update']);
        Route::delete('order/{type}/{id}', [OrderController::class, 'delete']);

        // zone/rule
        Route::get('zone/rule', [ZonePriceRuleController::class, 'getAll']);
        Route::post('zone/rule', [ZonePriceRuleController::class, 'create']);
        Route::put('zone/rule/{id}', [ZonePriceRuleController::class, 'update']);
        Route::delete('zone/rule/{id}', [ZonePriceRuleController::class, 'delete']);
                
        // Zone
        Route::get('zone', [ZoneController::class, 'getAll']);
        Route::post('zone', [ZoneController::class, 'create']);
        Route::put('zone/{id}', [ZoneController::class, 'update']);
        Route::delete('zone/{id}', [ZoneController::class, 'delete']);

        // Payment
        Route::get('payment', [PaymentController::class, 'getAll']);
        Route::post('payment', [PaymentController::class, 'create']);
        Route::put('payment/{id}', [PaymentController::class, 'update']);
        Route::delete('payment/{id}', [PaymentController::class, 'delete']);
        
//     });
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
