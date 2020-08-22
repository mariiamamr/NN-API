<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree; 

use Illuminate\Support\Facades\Auth;
class EditProfileController extends Controller
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
        /**
        * @group  edit profile
        * @bodyParam  exp_years string required experience years of a teacher. Example: 5
        * @bodyParam  exp_desc int  required The description of teacher's experience. Example: I have worked as a teacher for 7 years
        * update teacher's experience
        */
    public function updateExperience(Request $request){
        $request->validate([
            'exp_years' => 'required|numeric',
            'exp_desc' => 'required|string'
        ]);
        $user = Auth::user(); 
        //$profile=$this -> getProfile($user);
        $profile=$this -> getProfile($user);
        $profile->update(['exp_years'=>$request->exp_years,'exp_desc'=>$request->exp_desc]);
        $profile->save();
        return response()->json(['success' => $profile], $this-> successStatus); 
    
    
    
    }
            /**
        * @group  edit profile
        * @bodyParam  exp_years string required experience years of a teacher. Example: 5
        * @bodyParam  exp_desc int  required The description of teacher's experience. Example: I have worked as a teacher for 7 years
        * update teacher's experience
        */
        public function updateEducation(Request $request){
            $request->validate([
                'uni_degree_id' => 'required|numeric',
                'master_degree' => 'required|string',
                'courses' => 'required|string',
            ]);
            $user = Auth::user(); 
            //$profile=$this -> getProfile($user);
            $profile=$this -> getProfile($user);
            $uni_id=UniversityDegree::find($request->uni_degree_id);


            if(!$uni_id){
                return response()->json(['failure' => "the id of the university is not available"], $this-> successStatus);   
            }
            $profile->update(['uni_degree_id'=>$request->university_degree_id,'courses'=>$request->courses,'master_degree'=>$request->master_degree]);
            $profile->save();
            return response()->json(['success' => $profile], $this-> successStatus); 
        
        
        
        }
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
