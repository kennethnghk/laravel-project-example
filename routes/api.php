<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'v2', 'middleware' => 'token'], function () {

    Route::get('/merchants/{merchantId}', 'V2\MerchantController@getMerchant');
    Route::get('/merchants', 'V2\MerchantController@getMerchants');
});