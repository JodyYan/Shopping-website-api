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
Route::get('/seller/{id}', 'SellerController@show')->middleware('seller');
Route::patch('/seller/{id}', 'SellerController@update')->middleware('seller');
Route::delete('/seller/logout', 'SellerController@logout')->middleware('seller');
Route::post('/product', 'ProductController@store')->middleware('seller');
Route::get('/product', 'ProductController@index')->middleware('seller');
Route::get('/product/specific', 'ProductController@show')->middleware('seller');
Route::patch('/product/update/{product}', 'ProductController@update')->middleware('seller');
Route::delete('/product/delete/{product}', 'ProductController@destroy')->middleware('seller');
Route::post('/buyer', 'BuyerController@store');
Route::post('/buyer/login', 'BuyerController@login');
Route::middleware('buyer')->get('/buyer/{id}', 'BuyerController@show');
Route::patch('buyer/{id}', 'BuyerController@update')->middleware('buyer');
Route::delete('/buyer/logout', 'BuyerController@logout')->middleware('buyer');
Route::post('/shopping', 'ShoppingController@store')->middleware('buyer');
Route::get('/shopping', 'ShoppingController@index')->middleware('buyer');
Route::get('/shopping/{shopping}', 'ShoppingController@show')->middleware('buyer');
Route::delete('shopping/{shopping}', 'ShoppingController@destroy')->middleware('buyer');
Route::patch('shopping/track/{shopping}', 'ShoppingController@track')->middleware('seller');
