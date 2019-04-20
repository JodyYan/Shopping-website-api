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

Route::post('/seller', 'SellerController@store');
Route::post('/seller/login', 'SellerController@login');
Route::get('/seller/{id}', 'SellerController@show');
Route::patch('/seller/{id}', 'SellerController@update');
Route::delete('/seller/logout', 'SellerController@logout');
Route::post('/product', 'ProductController@store');
Route::get('/product', 'ProductController@index');
Route::get('/product/specific', 'ProductController@show');
Route::patch('/product/update/{product}', 'ProductController@update');
Route::delete('/product/delete/{product}', 'ProductController@destroy');
Route::post('/buyer', 'BuyerController@store');
Route::post('/buyer/login', 'BuyerController@login');
Route::get('/buyer/{id}', 'BuyerController@show');
Route::patch('buyer/{id}', 'BuyerController@update');
Route::delete('/buyer/logout', 'BuyerController@logout');
