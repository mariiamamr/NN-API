<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Carbon\Carbon;
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
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('My App');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
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
        $user = User::create($input); //create the user in the database
        $success['token'] =  $user->createToken('MyApp')-> accessToken; //create the access token
        $success['name'] =  $user->name;
return response()->json(['success'=>$success], $this-> successStatus); 
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
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $user = User::firstOrCreate([
            'email'     => $user->getEmail(),
        ],[
            'full_name' => $user->getName(),
            'birth'     => '2000-09-05',
            'gender'    => 'female',
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
        //return $user->getId();
    }
}