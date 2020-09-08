<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\Container\Contracts\Users\UsersContract;
use App\Container\Contracts\UserInfos\UserInfosContract;
use Illuminate\Support\Facades\Auth;

class PriceInfoController extends Controller


{
    protected $user;
    protected $user_info;

    public function __construct(UsersContract $user,UserInfosContract $user_info)
    {
        $this->user = $user;
        $this->user_info = $user_info;

       
    }
    /**
     * Edit price info
     * @group  edit profile
     * 
     * used to edit price in teacher profile
     *  
     *
     * @bodyparam price_info required Object of two prices includes individual price and group price written in json. Example: {"individual":"50","group":"150"}
     * @response 200{
     *"msg": "price pending"
    *}
     *
     * 
     */
    public function updatePriceInfo(Request $request)
   {
    $user = $this->user->updateTeacherProfile(Auth::id(), $request);
       /* $user = User::find(Auth::id());
        $user_id=$user->id;
        $status = $this->profile->changeTeacherPrice($user_id, $request->status);

        if ($status == 1) {
            $status_message = 'Approved';
        } else {
            $status_message = 'Rejected';
        }
       // $user = $this->user->get($id);
        //if ($user) {
            $message = 'Your price for the session has been ' . $status_message . '';
           // $user->notify(new ApproveUser($message));
       // }*/
        return response()->json(["msg"=>"price pending" ],200); 

    }

       /* $this->validate($request, [
            'price_info'=>'required',
            'status'=>'boolean'
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
          
      }*/
}
