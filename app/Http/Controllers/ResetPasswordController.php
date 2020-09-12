<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request; 

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
      /**
     * Reset Password
     * @group  Password
     * 
     * used to send a reset link to the required email.
     *  
     * @bodyParam  email string the email associated with the account whose password will be forgotten. a link will be sent to that address.
     * @bodyParam  password string the new password.
     * @bodyParam  password_confirmation string the new password repeated. it must match "password" field.
     * @bodyParam  token string token sent in the forget password email.
     * @response {
     *  "message": "Your password has been reset!"
     *  }
     * @response 422{
     *  "error": "We can't find a user with that e-mail address."
     * }
     * @response 422{
     *  "error": "This password reset token is invalid."
     * }
     * 
     */
    protected function sendResetResponse(Request $request,$response)
    {
        return response()->json(['message' => trans($response)], 200);
    }

    protected function sendResetFailedResponse(Request $request,$response)
    {
        return response()->json(['error' => trans($response)], 422);
    }

}
