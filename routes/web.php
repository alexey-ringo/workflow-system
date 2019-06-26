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
Route::get('/{any}', 'DashboardController@index')->where('any', '.*');
//Route::get('/a7dm0in3{any}', 'AdminController@index')->where('any', '.*');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

