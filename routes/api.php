<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesTrendController;
use App\Http\Controllers\SalesDistributionController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//查询monthly invoice amount的路径
Route::apiResource('salestrend', SalesTrendController::class);

//查询invoice amount distribution的路径
Route::apiResource('salesdistribution', SalesDistributionController::class);

