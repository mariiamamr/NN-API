<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use Illuminate\Support\Facades\Auth;

class PriceInfoController extends Controller
{
    public function updatePriceInfo(Request $request)
    {
        
        $this->validate($request, [
            'price_info'=>'required',
        ]);

        $user = User::find(Auth::id()); //Find auth user
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
      
       return response()->json($userInfo->price_info,201); //Created and response returned price info
          
      }
}
