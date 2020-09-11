<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\Container\Contracts\Options\OptionsContract;
use App\Container\Contracts\Users\UsersContract;
use App\Container\Contracts\UserInfos\UserInfosContract;
use Illuminate\Support\Facades\Auth;

class PriceInfoController extends Controller


{
    protected $user;
    protected $option;


    public function __construct(UsersContract $user,OptionsContract $option )
    {
        $this->user = $user;
        $this->option = $option;
       
    }
    /**
     * Add other price
     * @group  edit profile
     * 
     * used to edit price in teacher profile
     *  
     *
     * @bodyParam price_info required Object of two prices includes individual price and group price written in json. Example: {"individual":"50","group":"150"}
     * @response 200{
     *"msg": "price pending"
    *}
     *
     * 
     */
    public function updatePriceInfo(Request $request)
   {
    $user = $this->user->updateTeacherProfile(Auth::id(), $request);
        return response()->json(["msg"=>"price pending" ],200); 

    }
       /**
     * Get prices approved by the admin from options table
     * @group  getters
     * 
     * used to get prices
     *  
     * @response 200{
     *"price": "{\"individual\":[\"50\",\"100\"],\r\n\"group\":[\"100\",\"200\"]}"
     *}
     * @response 400{
     *"error": "Unauthorized"
     *}
     * 
     */
    public function getPrice(){
        $user = User::find(Auth::id());
        if(!$user){
            return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
        }
        $prices=$this->option->getByName('prices');
        return response()->json(["prices"=>$prices->value],200); 



    }
}
