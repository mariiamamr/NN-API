<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo ; 

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Carbon\Carbon;
class UserController extends Controller 
{
public $successStatus = 200;
public function getProfile($user){
    $profile=$user->profile;
    if(!$profile)
{
$profile = new \App\UserInfo;
$user->profile()->save($profile);
    }
   return UserInfo::firstOrCreate(['user_id'=>$user->id]);


}
public function updateExperience(Request $request){
    $request->validate([
        'exp_years' => 'required',
        'exp_desc' => 'required'
    ]);
    $user = Auth::user(); 
    //$profile=$this -> getProfile($user);
    $profile=$this -> getProfile($user);
    $profile->update(['exp_years'=>$request->exp_years,'exp_desc'=>$request->exp_desc]);
    $profile->save();
    return response()->json(['success' => $profile], $this-> successStatus); 



}

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
                    'message' => 'Unauthorized'
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
        $user = User::create($input);//->SendEmailVerificationNotification(); 

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
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        //return Socialite::driver('facebook')->redirect();
        return Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday','name'
        ])->scopes([
            'email', 'user_birthday','user_gender'
        ])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
       // $user = Socialite::driver('facebook')->user();
       $user = Socialite::driver('facebook')->fields([
        'first_name', 'last_name', 'email', 'gender', 'birthday','name'
    ])->user();
       $user = User::firstOrCreate([
            'email'     => $user->getEmail(),
        ],[
            'full_name' => $user->getName(),
            'birth'     => Carbon::parse($user['birthday'])->format('Y-m-d'),
            'gender'    => $user['gender'],
            'password'  => Hash::make($user->getId()),
            'type'      => 's'
        ]);
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