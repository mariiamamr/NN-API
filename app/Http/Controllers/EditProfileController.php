<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use Illuminate\Support\Facades\Auth;
class EditProfileController extends Controller
{
    /**
     * Update user info
     *
     * @param  [string] full_name
     * @param  [string] gender
     * @param  [boolean] birth
     * @param  [numeric] phone
     * @param  [string] nationality
     * @return [json] user
     */
    public function updateAuthUser(Request $request)
    {
        
        $this->validate($request, [
            'full_name'=>'required|string',
           // 'username' => 'required|string|unique:users',
            'gender'=>'required|in:male,female',
            'birth'=>'required',
            'phone'=>'required|numeric|min:11',
            'nationality'=>'required|string'
        ]);

        $user = User::find(Auth::id());
    //Updating UserInfo
        $userInfo=$user->profile;
        if(!$userInfo){
            UserInfo::create([
                'user_id'=>$user->id,
                'phone'=>$request->phone,
                'nationality'=>$request->nationality,
            ]);
        }
        else{
            $userInfo->update([
                'phone' => $request->phone,
                'nationality'=>$request->nationality,
            ]);
        }
        // Updating User Related Data
       // $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->gender = $request->gender;
        $user->birth = $request->birth;
        $user->save();
      
       return response()->json($user,201); //Created
          
      }
}
