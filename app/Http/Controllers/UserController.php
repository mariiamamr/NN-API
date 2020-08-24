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
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
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
            //$user->remember_token=$tokenResult->accessToken; //store token in remember_token column
            //$user->save();
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
     
     * Create user
     *
     * @param  [string] full_name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] username
     * @param  [string] type
     * @param  [int] birth
     * @return [string] access_token
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
        // $user = User::create($input);//->SendEmailVerificationNotification(); 
        event(new Registered($user = User::create($input)));

        $success['token'] =  $user->createToken('MyApp')-> accessToken; //create the access token
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus); 

    }

     /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
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