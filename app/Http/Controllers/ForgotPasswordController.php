<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request; 


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

      /**
     * Forget Password
     * @group  Password
     * 
     * used to send a reset link to the required email.
     *  
     * @bodyParam  email the email associated with the account whose password will be forgotten. a link will be sent to that address
     * @response {
     *  "message": "We have e-mailed your password reset link!"
     *  }
     * @response 422{
     *  "error": "We can't find a user with that e-mail address."
     * }
     * 
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(['message'=> trans($response)]);

    }


    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['error'=> trans($response)], 422);

    }

}
