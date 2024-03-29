<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo ; 
use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Carbon\Carbon;
use File;
class UserController extends Controller 
{
public $successStatus = 200;


      /**
     * Login
     * used to login and create token
     * @group  authentication
     * 
     *  
     *
     * @bodyParam username string required "this will be username or email"
     * @bodyParam password string required
     * @bodyParam remember_me boolen required with value 1 or 0 
     * @response 200{
     *  
     *      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTg1M2U3MjNmMjBlNDg3MDBlNDhkYTU2ZmU2MDQ3MGU3ZGFmNjM2YTRmNmM3NTAyYWY3NGM3YTQzYzQyZWM2NmY0NDEzYTY2MTczMTdlZWIiLCJpYXQiOjE1OTk1MDAyMzgsIm5iZiI6MTU5OTUwMDIzOCwiZXhwIjoxNjMxMDM2MjM4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Fo0udtMETBRLa4hYX99uErc7eOxTkPAFvaUffpogHnBo2xAMAwRyq-u15L2Hx510kQS2RqlHhOdzuSvIbtPIYJ6OyjlbP9XQxBSbVEKo3Pcbr9twTrAmwPifpEgc3zT9q_NRrnm9UabzfMy3-5tCwvGdNAv3yZet4CjVqTF-7lmFIt2MjSH1Si2WxlGa8Y3DMzvr0t4PuA8_ju8MK5Ql8ylNF10DQyi2YbbULXVHNJXKYIqDRElsAhzN185GTxYHvudvH_VIPOHMCkUeR4i5FAPHkhB_PGSrF9nde6CfbAQ7GIkiC5q9-wB4_Dt5sYjAX1y0VqUiL-y0V99XKS88_1AWkue2W1YfsxI76hcmTIGUR_57IxWVJPNlGXPzpUGdsHlKBmyH7mIHmo8wVMIq3woEy2ilfCLqyVAMIca-94nqY7iqmjhlrE_rBgvfpRz19n2AOWgI9Q33SrNYR4MM_g9XONXpYsjbpAz5BzahWbLRALTqGQNgKy7GNJbMld6Q0jKrZqek0T7Tb6sP1jSgWQaLz5VBhUJvZRDW2zO6-acBg3yQvRTqyMVeFigZaG4Rx9CnH-xd40WeeEjhA--uyCj0XD2zfhdPxNLhYvFa3tCYCJJwuffogpkAcd0pwuUsPS1Rvw75z5AqObFWiYqmwWDbwyrpF_xsVOUWIrqHxX0",
     *      "token_type": "bearer",
     *      "expires_at": "YYYY-MM-DD"
     * }
     * @response 401{
     *      "error" : "Unauthorized"
     * }
     * 
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
                $username = $request->username;
                if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
                    //user sent their email 
                   $user= Auth::attempt(['email' => $username, 'password' => $request->password]);
                } else {
                    //they sent their username instead 
                    $user=Auth::attempt(['username' => $username, 'password' => $request->password]);
                }
            
            if(!$user){
                return response()->json([
                    'error' => 'Unauthorized'
                ], 401);
                        }
                        
        $user = $request->user();
        $tokenResult = $user->createToken('My App');
        $token = $tokenResult->token;
        if ($request->remember_me){
            $token->expires_at = Carbon::now()->addDays(365);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString() //will change the expiration date 
        ]);
    }
 

    
   /**
     * Signup
     * used to register a user and create token. An email verification notification is sent to the registered address. the verification link sent in the email has to be modified by frontend in CustomVerifyEmailNotification.php 
     * @group  authentication
     * 
     *  
     *
     * @bodyParam username string required Unique string for every user
     * @bodyParam email string required Unique string for every user
     * @bodyParam password string required Minimun 6 char
     * @bodyParam type string required The type of the user teacher "t" or student "s". Example: s
     * @bodyParam birth numeric required Must be in format "YYYY-MM-DD"
     * @bodyParam gender string required The gender of the user "male" or "female". Example: female
     * @bodyParam full_name string required Must be a string 
     * @bodyParam password_confirmation string required Must be a the same as the password 
     * @response 200{
     *       "success": {
     *   "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMzA0NWJlMmY0MjNhZTU0YTI3NjFjNDYyNWM1ZDM2ZmRmYzk5MWJiYjRkOWZlZDRiMTQ5YmQ4MjAwODExOWZkZDBhMWMyNjE3MDhkZGYwNGUiLCJpYXQiOjE1OTk1MDg4NzAsIm5iZiI6MTU5OTUwODg3MCwiZXhwIjoxNjMxMDQ0ODcwLCJzdWIiOiI3Iiwic2NvcGVzIjpbXX0.EarO8YHGHKdsDD3QVcBoT0L4bIztSJRlzE0jLM15xOJWOsUe1-1Xguolc9aPi4nADQ-spMstP0INNVE9Tyw6T-AuwqIP6I2YhFGh8eEVUFTXqaC29SR-CH53Bch5k-pXpwEdWoGgePSes-EC6ZntlmfGXEmAMhcmhvB_iddXqVaxSzxph-PBX9q78pYsozxSZVeZd0WwndCjzZOS5wJkBD70W6tQuINYVc3pgz60w585Ns2bzIDxBJZHDtyOcnZyOYbGiJDIu-0c8dyorj8q8XfnjHnd43ImBYjgZR5dIM7Ymo37Q62CD_lv1ex9zJFXhCSFn3QYSDesPlvy_l-7tugSwHMKVIqDBfJBxG9gQxO_yjoZwWYJmysqmA6crcHjZLcW-HVoOPnx6cak6MyyTvAcX7mWDBJ0Te5HV3ALK8ZFHhPae2qmB-H1TvPyxlHdY0cYIkEiGfIgX1pijIbbbeBq7EcP9dO6JAEWaD5SDKvc0tn7Se09AXS1fygxgAWgvazev4V06aZG-_t5F0S6jbCBnKHmx9f9NsDZahAAppnUX7VZ2mA5yOGzo3UEJxz3tA9EIGv2zPgrKPZE4AMaJzsR-_vL7KfHxJfmoberYs0pbGyUMi_K-GtaOnAqdE0XDNsfLRs9ACkH2rCeTuwwCgRHlLy9R9DIQ5nIeRrmQsc",
     *   "name": "mariam"
     * }
     * }
     * @response 401{
     *      "error" : "a specific error will be displayed here"
     * }
     */
    public function register(Request $request) 
    { 
        //Validating signup form before make the request
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|string|email|unique:users', 
            'password' => 'required|string|min:6|confirmed', 
            'username' =>'required|string|unique:users',
            'full_name'=>'required|string',
            'gender'=>'required|in:male,female',
            'type'=>'required|in:t,s',
            'birth'=>'required',
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);        //unauthorized    
        }
$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 

        //create the user in the database and send email verification message
       event(new Registered($user = User::create($input)));

        $success['token'] =  $user->createToken('MyApp')-> accessToken; //create the access token
        $success['name'] =  $user->full_name;
return response()->json(['success'=>$success], $this-> successStatus); 

    }

     /**
     * @authenticated
     * @group  authentication
     * Logout user (Revoke the token)
     *
     * @response 200{
     * "message":"Successfully logged out"} 
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
/** 
     * details api *get users*
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
      
    public function m() 
    { 
        return "m"; 
    } 
/**
 * In order to save the user's avatar
 * REMEMBER TO ADD "use File; use Illuminate\Support\Carbon; use DB;" TO TOP! 
 * @param  $avatar Socialite user's avatar ($user->getAvatar())
 * @param $userId User's id database
 */
public function saveImageAvatar($avatar, $userId)
{
    $fileContents = file_get_contents($avatar);
    $path = public_path() . '/users/images/' . $userId . "_avatar.jpg";
    File::put($path, $fileContents);
    return $path;
}
    /**
    * @group  authentication
    *
    * login with facebook
    * @queryParam  type required The type of the user teacher "t" or student "s". Example: s
    */
    public function redirectToProvider()
    {

        $type=request('type');
        //return $type;
        //return Socialite::driver('facebook')->redirect();
        return Socialite::driver('facebook')->with(['state' => $type])->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday','name'
        ])->scopes([
            'email', 'user_birthday','user_gender'
        ])->redirect();
    }

 
    /**
    * @group  authentication
    *
    * redirected from the login/facebook request 
    * @response {
    *    "access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiOTI1NDRmNGNjZDI4NmUwY2Y5MGUyMzU4NjllMmRhMTU5ODI4MTk2Nzg0MWYwZDhhMDUyZGRjNTM0YTRmY2E2YTU2NWE5NDA5YjA2ZTU2NTEiLCJpYXQiOjE1OTgwNTM4NTQsIm5iZiI6MTU5ODA1Mzg1NCwiZXhwIjoxNjI5NTg5ODUzLCJzdWIiOiI2Iiwic2NvcGVzIjpbXX0.lqlGeL1mF6Xt8mTXqnl3HMLi6KkSK2C519qUZkxcrNhKD6dQ60ZHbjmDrXth01FQP8VianigA2bhu6YeY7n4MCbtqMVvbkgxHi7FiHh4a8YXdqcgrBK7t79U4waFhMnLxqYJ8YRPLyn2Jdn7qfKmmevRoxvOvwOcn19TFjVXs1KthRMpvSotwhnc6ExaN0oBN7VdjAKIPnmNQCQ77ZT6KayF-Q8NBf_bz-ENP3y-NTtdfOETd-SPaqGtHAtQxdRrqMUNKIfAgUdVHxO4Mwzv_vayR8a6-aeNShHWl-DCGsTu5c5KE1yum4ALHAxS90VIWTKrNM_P_kwmG91tjbtEnlNWzYjM2rO1Gu9MreMyyVGOjIcwRXdjoGJIw7YebZZ1f0E_v9XBlJ2Wme6KmRFyJpK7qZFY7t_Sk3oenuVaR4qJURNmUznktD_9BxGyKRQ669qjYp1PwbOE_5KxiSOhibO7CVUuHPyAz2tYniX-VN07q462FOv4K4e7a2ifQLyMM8f5Fx9lTL4UF-MxpPNTr6xIXuHE62eu6eZPUgEiDAzjS9JJLAEt7KGwnt-8rumnM6nKFsBqRovHt449dQxGJzw-9RiOecKs744cof0RbZfAf_pXrE0oGnDYx-4_mYvMvBpZ8ykAQuSqDKLtKAaro8FMiYbDAsAcOVC9kczgl14",
    *    "token_type":"Bearer",
    *    "expires_at":"2021-08-21 23:50:53"
    * }
    */
    public function handleProviderCallback()
    {
        
        $type = request()->input('state');
       // $user = Socialite::driver('facebook')->user();
       $user = Socialite::driver('facebook')->stateless()->fields([
        'first_name', 'last_name', 'email', 'gender', 'birthday','name'
    ])->user();
    $url=$this->saveImageAvatar($user->getAvatar(), $user->getId());
       $user = User::firstOrCreate([
            'email'     => $user->getEmail(),
        ],[
            'full_name' => $user->getName(),
            'birth'     => Carbon::parse($user['birthday'])->format('Y-m-d'),
            'gender'    => $user['gender'],
            'password'  => Hash::make($user->getId()),
            'type'      => $type,
            
        ]);
        $user->image_url = $url;
        $user->save();
        $tokenResult = $user->createToken('My App');
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString() //will change the expiration date 
        ]);
    }
}