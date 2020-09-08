<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use Illuminate\Support\Facades\Auth;

class PriceInfoController extends Controller
{
    /**
     * Edit price info
     * @group  edit profile
     * 
     * used to edit price in teacher profile
     *  
     *
     * @bodyparam price_info required Array of two prices includes individual price and group price written in json. Example: [150,250]
     * @response 200{
     *    "price_info": "{\"individual\":150,\"group\":250}"
     * }
     * @response 401{
     *      "error" : 'Unauthorized'
     * }
     * 
     */
    public function updatePriceInfo(Request $request)
    {
        
        $this->validate($request, [
            'price_info'=>'required',
        ]);

        $user = User::find(Auth::id()); //Find auth user
        if(!$user){
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }
    //Updating UserInfo
        $userInfo=$user->profile;
        if(!$userInfo){
            UserInfo::create([
                'user_id'=>$user->id,
                'price_info'=>json_encode([
                    "individual" => $request->price_info[0],
                    "group" => $request->price_info[1],
            ])
            ]);
        }
        else{
            $userInfo->update([
                'price_info'=>json_encode([
                    "individual" => $request->price_info[0],
                    "group" => $request->price_info[1],
            ])
            ]);
        }
   
        $userInfo->save();
      
       return response()->json(["price_info"=>$userInfo->price_info],200);
          
      }
}
