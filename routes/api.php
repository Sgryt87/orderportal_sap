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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('orders', 'OrderController');

Route::post('orders-bulk-store', 'OrderController@storeBulk');

Route::post('address-by-nsn-single', 'ItImportController@getAddressByNSN');

Route::post('order-validate-single', 'OrderController@validateSingle');

Route::post('address-by-nsn-bulk', 'ItImportController@getAddressesByNSN');

Route::post('orders-validate-bulk', 'OrderController@validateBulk');

Route::apiResource('it-imports', 'ItImportController')->only(['index', 'store']);

Route::apiResource('it-exports', 'ItExportController')->only(['index']);

Route::apiResource('order-boards', 'OrderBoardController');

Route::apiResource('presells', 'PresellController');

Route::apiResource('protective-covers', 'ProtectiveCoverController');

Route::apiResource('height-requirements', 'HeightRequirementController');
