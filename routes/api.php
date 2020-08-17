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


 //Auth system routes
 Route::post('login', 'UserController@login');
 Route::post('register', 'UserController@register');
 Route::group(['middleware' => 'auth:api'], function(){
     Route::post('details', 'UserController@details');
     });
     Route::group(['middleware' => ['web']], function () {
        Route::get('login/facebook', 'UserController@redirectToProvider');
        Route::get('login/facebook/callback', 'UserController@handleProviderCallback');
        });

Route::get('m', 'UserController@m');

