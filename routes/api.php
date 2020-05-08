<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:api')->get('test', function(Request $request){
    return 'You are authenticated!';
});

Route::middleware('auth:api')->get('/totalBalance', 'AssetController@getAssetsValue');
Route::middleware('auth:api')->get('/asset/get', 'AssetController@get');
Route::middleware('auth:api')->post('/asset/post', 'AssetController@post');
Route::middleware('auth:api')->put('/asset/put', 'AssetController@put');
Route::middleware('auth:api')->delete('/asset/delete', 'AssetController@delete');
