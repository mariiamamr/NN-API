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
    Route::get('logout', 'UserController@logout'); //will see
     Route::get('details', 'UserController@details');
     //Edit Profile
     Route::get('/getexperience','UserController@getexperience');
     Route::put('/updateexperience','UserController@updateExperience');

     Route::post('edit','EditProfileController@updateAuthUser');
     });
 Route::get('/email/resend','VerificationController@resend')->name('verification.resend');
 Route::get('/email/verify/{id}/{hash}','VerificationController@verify')->name('verification.verify');


     Route::group(['middleware' => ['web']], function () {
        Route::get('login/facebook', 'UserController@redirectToProvider');
        Route::get('login/facebook/callback', 'UserController@handleProviderCallback');
        });

Route::get('m', 'UserController@m');
