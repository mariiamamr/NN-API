<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use Illuminate\Support\Facades\Auth;
class EditProfileController extends Controller
{
    public function updateAuthUser(Request $request)
    {
        $this->validate($request, [
            'full_name'=>'required|string',
            'username' => 'required|string|unique:users',
            'gender'=>'required|in:male,female',
            'birth'=>'required',
            'phone'=>'required|string',
            'nationality'=>'required|string'
            //'email' => 'required|email|unique:users,email,'.Auth::id()
        ]);
        $user = User::find(Auth::id());
        $user->username = $request->username;
        $user->full_name = $request->full_name;
        $user->gender = $request->gender;
        $user->birth = $request->birth;
        $user->save();
       
          return response()->json($user,200);
      }
}
