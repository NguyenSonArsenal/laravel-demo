<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/vnpay', ['uses' => 'VnPayController@getVnpay']);
Route::post('/checkout-vnpay', ['as' => 'vnpay.post', 'uses' => 'VnPayController@checkout']);
Route::get('/return-vnpay', ['uses' => 'VnPayController@returnVnpay']);
