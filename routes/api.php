<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\SolariumController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\WarehouseController;
use \App\Http\Controllers\CompanyController;

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

Route::get('/solr/ping', [SolariumController::class, 'ping']);
Route::get('/solr/search', [SolariumController::class, 'search']);

//Product: Routing 
Route::get('products', [ProductController::class, 'getAll']);
Route::get('product/{id}', [ProductController::class, 'get']);
Route::post('product', [ProductController::class, 'create']);
Route::put('product/{id}', [ProductController::class, 'update']);
Route::delete('product/{id}', [ProductController::class, 'delete']);

//Product: Company 
Route::get('company', [CompanyController::class, 'getAll']);
Route::get('company/{id}', [CompanyController::class, 'get']);
Route::post('company', [CompanyController::class, 'create']);
Route::put('company/{id}', [CompanyController::class, 'update']);
Route::delete('company/{id}', [CompanyController::class, 'delete']);

//Product: Warehouse 
Route::get('warehouse', [WarehouseController::class, 'getAll']);
Route::get('warehouse/{id}', [WarehouseController::class, 'get']);
Route::post('warehouse', [WarehouseController::class, 'create']);
Route::put('warehouse/{id}', [WarehouseController::class, 'update']);
Route::delete('warehouse/{id}', [WarehouseController::class, 'delete']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

