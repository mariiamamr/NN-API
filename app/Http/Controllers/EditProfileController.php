<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree;
use Validator;
use File;
use App\Container\Contracts\Users\UsersContract;

use Illuminate\Support\Facades\Auth;
class EditProfileController extends Controller
{

    protected $user;
    public function __construct( UsersContract $user)
    {
      $this->user = $user;

    }
    protected $addCertificationValidationMessages = [
        'certifications.*.thumb.mimes' => 'The Certifications File must be a file of type: jpeg, jpg, png, pdf, svg.',
    ];
    protected $addCertificationValidationRules  = [
        'certifications.*.thumb' => 'mimes:jpeg,jpg,png,pdf,svg',
    ];
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
    public function uploadCertificate($file,$userid,$certificateid)
    {
        $fileName="";
        if ($file && $file->isValid()) {
          /*  $extension = $file->getClientOriginalExtension();
            $fileNameWithoutExtension=time().rand(11111,99999);
            $filePath=$fileNameWithoutExtension.'.'.$extension;
            $fileName ='/users/certificates/'.$filePath;
            $file->move(public_path().'/users/certificates/',$fileName);*/
            $fileContents = file_get_contents($file);
            $fileName = '/users/certificates/' . $userid . $certificateid.'.jpg';
            $path = public_path() .$fileName;
           // $file->move($path,$fileName);

            File::put($path, $fileContents);
        }
        return $fileName;
    }
          public function updateCertificates(Request $data){
            $user = Auth::user(); 

            //Validation for uploading file extenstions
            $data->validate($this->addCertificationValidationRules, $this->addCertificationValidationMessages);

            // Array for save the ids of files in uploading
            $certificates_array = [];
            foreach ($data->certifications as $key => $certificate) {
                //return $data->certifications;
                if (isset($certificate['thumb'])) {
                    $file = $certificate['thumb'];
                    if ($file->isValid()) {
                        $fileName = $this->uploadCertificate($file,$user->id,$certificate['certificate_id']);
                        $certificates_array[$key] = [
                            'certificate_id' => $certificate['certificate_id'],
                            'thumb_name' => $fileName                        ];
                    }
                } else {
                    $certificates_array[$key] = [
                        'certificate_id' => $certificate['certificate_id'] ? $certificate['certificate_id'] : null,
                        'thumb_name' => $certificate['thumb_name'] ? $certificate['thumb_name'] : null
                    ];
                }
            }
            $data->certifications = json_encode($certificates_array);
            $profile=$this -> getProfile($user);
            $profile->update([
                'certifications' => $data->certifications
            ]);            $profile->save();
            return json_decode($profile->certifications);
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

      public function updateSubjects(Request $request)
      {
      
          $user = $this->user->updateTeacherProfile(Auth::id(), $request);
        
          return response()->json($user,200); //Updated
        
      }


}
