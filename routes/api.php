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
    Route::post('uploadprofilepicture', 'EditProfileController@uploadProfilePicture');

    Route::get('logout', 'UserController@logout'); //will see
     Route::get('details', 'UserController@details');
     Route::get('getprofile', 'GetProfileController@getUserProfile');


     //Edit Profile
     //Setters
     Route::post('editprofile','EditProfileController@updateAuthUser');
     Route::post('priceinfo','PriceInfoController@updatePriceInfo');
     Route::post('/updatecertificates','EditProfileController@updateCertificates');
     Route::put('/updateexperience','EditProfileController@updateExperience');
     Route::put('/updateeducation','EditProfileController@updateEducation');
     Route::post('/updatesubjects','EditProfileController@updateSubjects');
     //Getters
     Route::get('/getexperience','UserController@getexperience');
     Route::get('/getsubjects','GetProfileController@getSubjects');
     Route::get('/getlanguages','GetProfileController@getLanguages');
     Route::get('/getedusystems','GetProfileController@getEdusystems');
     Route::get('/getgrades','GetProfileController@getGrades');


     //sessions
     Route::get('getpastsessionsforstudents','CreateSessionController@getPastSessionsForStudents');
     Route::get('getupcomingsessionsforstudents','CreateSessionController@getUpcomingSessionsForStudents');
     Route::get('getpastsessionsforteachers','CreateSessionController@getPastSessionsForTeachers');
     Route::get('getupcomingsessionsforteachers','CreateSessionController@getUpcomingSessionsForTeachers');
     Route::post('createsession','CreateSessionController@createSession');
     Route::post('updatesession','CreateSessionController@update');
     Route::delete('deletesession','CreateSessionController@destroy');
     Route::post('enrollsession','CreateSessionController@enroll');
     Route::get('availabledays','CreateSessionController@available_days');
     Route::get('availableslots','CreateSessionController@available_slots');



     //ratings
     Route::post('/ratingbystudent','ReviewsController@ratingByStudent');
     Route::post('/ratingbyteacher','ReviewsController@ratingByTeacher');

     //home page
     Route::get('/home','HomeController@approvedTeachers');


     });
//email verification for new users
 Route::get('/email/resend','VerificationController@resend')->name('verification.resend');
 Route::get('/email/verify/{id}/{hash}','VerificationController@verify')->name('verification.verify');

 //forgetting password
 Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail');
 Route::post('/password/reset','ResetPasswordController@reset');

     Route::group(['middleware' => ['web']], function () {
        Route::get('login/facebook', 'UserController@redirectToProvider');
        Route::get('login/facebook/callback', 'UserController@handleProviderCallback');
        });

Route::get('m', 'UserController@m');
