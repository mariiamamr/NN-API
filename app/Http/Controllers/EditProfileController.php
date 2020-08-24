<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree;
use Validator;
use File;


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
        * @authenticated
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
        * @authenticated
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
    * @group  edit profile
    * @authenticated
    * upload profile picture
    * @bodyParam  photo  file required the profile picture 
    * @response {
    *"success": {
    *    "id": 17,
    *    "email": "aya_1999_mahmoud@hotmail.com",
    *    "email_verified_at": null,
    *    "created_at": "2020-08-23 22:13:11",
    *    "updated_at": "2020-08-23 22:13:55",
    *    "full_name": "Aya Mahmoud",
    *    "type": "s",
    *    "active": 1,
    *    "birth": "1999-09-09",
    *    "gender": "female",
    *    "status": null,
    *    "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public/users/images/17_avatar.jpg",
    *    "username": null
    *}
    * }
    */
    public function uploadProfilePicture(Request $request){
        //Validating signup form before make the request
        $validator = Validator::make($request->all(), [ 
         'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
     ]);
 if ($validator->fails()) { 
         return response()->json(['error'=>$validator->errors()], 401);        //unauthorized    
     }
         $fileContents = file_get_contents($request->file('photo'));
         $user = Auth::user(); 
         $path = public_path() . '/users/images/' . $user->id . "_avatar.jpg";
         File::put($path, $fileContents);
         $user->image_url = $path;
         $user->save();
         return response()->json(['success'=>$user], $this-> successStatus); 
 
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
