<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
Route::get('auth/login',function(){
   return redirect('login');
});
Route::get('/',function(){
   return redirect('home');
});

// Registration routes...
Route::get('register','Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');


Route::get('home','HomeController@index');
//Route::get('dashboard', 'UserController@index');
Route::post('store-items','UserController@store');
Route::get('items','UserController@items');
Route::post('post-data','UserController@postStorePrice');


