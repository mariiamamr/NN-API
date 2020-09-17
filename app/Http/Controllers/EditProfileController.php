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
        * update teacher's experience
        * @group  edit profile
        * @bodyParam  exp_years string required experience years of a teacher. Example: 5
        * @bodyParam  exp_desc Integer  required The description of teacher's experience. Example: I have worked as a teacher for 7 years
        * @response {
        * "success": {
        * "id": 32,
        * "user_id": 17,
        * "nationality": null,
        * "phone": null,
        * "postal_code": null,
        * "exp_years": "5",
        * "exp_desc": "I love me",
        * "payment_info": null,
        * "avg_rate": 0,
        * "month_rate": 0,
        * "rank": 0,
        * "rates_count": 0,
        * "courses": null,
        * "certifications": "[{\"certificate_id\":\"photo\",\"thumb_name\":\"files\\/159957261167645.jpg\"}]",
        * "master_degree": null,
        * "weekly": [
        *    {
        *        "on": "wed",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "07:30",
        *                "started_from": "2020-09-30"
        *            },
        *            {
        *                "time_from": "02:30",
        *                "time_to": "03:40",
        *                "started_from": "2020-07-29"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "mon",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "06:40",
        *                "started_from": "2020-11-30"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "thu",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "05:50",
        *                "started_from": "2020-1-30"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "sat",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "06:40",
        *                "started_from": "2021-1-30"
        *            }
        *        ]
        *    }
        * ],
        * "university_degree_id": null,
        * "price_info": null,
        * "national_id": null,
        * "phones": null,
        * "suggested_subjects": null,
        * "other_subjects": null
        * }
        * }
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
    public function uploadCertificate($file)
    {
        $fileName="";
        if ($file && $file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            $fileNameWithoutExtension=time().rand(11111,99999);
            $filePath=$fileNameWithoutExtension.'.'.$extension;
            $fileName ='files/'.$filePath;
            $file->move(public_path().'/files/',$fileName);
        }
        return $fileName;
    }
        /**
        * @authenticated
        * update certifications of the user
        * @group  edit profile
        * @bodyParam  certifications array required array of certifications the user adds where each object contains certificate_id(the id that refers to the certificate in certificate table)and thumb(the file of the certificate). Example: [{'certificate_id':1,'thumb':image.jpg}]
        * @response {
        * "success": [
        * {
        *    "certificate_id": "photo",
        *    "thumb_name": "files/160011349783963.jpg"
        * }
        * ]
        * }
        */
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
           // return json_decode($profile->certifications);
           return response()->json(['success' => json_decode($profile->certifications)], $this-> successStatus); 
          }  
        /**
        * @authenticated
        * @group  edit profile
        * update teacher's education
        * @bodyParam  uni_degree_id Integer required university degree id of the teacher. Example: 1
        * @bodyParam  master_degree string required master degree of the teacher. 
        * @bodyParam  courses string  required courses of the teacher.
        * @response {
        * "success": {
        * "id": 32,
        * "user_id": 17,
        * "nationality": null,
        * "phone": null,
        * "postal_code": null,
        * "exp_years": 5,
        * "exp_desc": "I love me",
        * "payment_info": null,
        * "avg_rate": 0,
        * "month_rate": 0,
        * "rank": 0,
        * "rates_count": 0,
        * "courses": "ef.re;fr.fr.e'",
        * "certifications": "[{\"certificate_id\":\"photo\",\"thumb_name\":\"files\\/159957261167645.jpg\"}]",
        * "master_degree": "fdl[d]",
        * "weekly": [
        *    {
        *        "on": "wed",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "07:30",
        *                "started_from": "2020-09-30"
        *            },
        *            {
        *                "time_from": "02:30",
        *                "time_to": "03:40",
        *                "started_from": "2020-07-29"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "mon",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "06:40",
        *                "started_from": "2020-11-30"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "thu",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "05:50",
        *                "started_from": "2020-1-30"
        *            }
        *        ]
        *    },
        *    {
        *        "on": "sat",
        *        "at": [
        *            {
        *                "time_from": "05:30",
        *                "time_to": "06:40",
        *                "started_from": "2021-1-30"
        *            }
        *        ]
        *    }
        * ],
        * "university_degree_id": null,
        * "price_info": null,
        * "national_id": null,
        * "phones": null,
        * "suggested_subjects": null,
        * "other_subjects": null
        * }
        * }
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
     * Edit profile
     * used to edit full name, birth, gender, phone, nationality
     * @authenticated
     * @group  edit profile
     *  
     *
     * @bodyParam full_name String required
     * @bodyParam birth date required Must be in format "YYYY-MM-DD"
     * @bodyParam gender String required The gender of the user "male" or "female". Example: female
     * @bodyParam phone Integer Must be a numeric 
     * @bodyParam nationality String user's nationality
     * @response 200{
     *      "user": [
     *   "mariam",
     *  "female",
     *   "1999-11-11",
     *  null,
     *   "egyptian"
     *  ]
     * }
     * 
     */
    public function updateAuthUser(Request $request)
    {
        
        $this->validate($request, [
            'full_name'=>'string',
            'gender'=>'in:male,female',
            'phone'=>'numeric|min:11',
            'nationality'=>'string'
        ]);

        $user = User::find(Auth::id());
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
        $user->full_name = $request->full_name;
        $user->gender = $request->gender;
        $user->birth = $request->birth;
        $user->save();
      
       return response()->json(["user"=>[$user->full_name,$user->gender,$user->birth,
       $userInfo->phone,$userInfo->nationality]],200); //Created
          
      }

      /**
     * @authenticated
     * Update subjects
     * used to update subjects
     * @group  edit profile
     * 
     *  
     *
     * @bodyParam subjects array Array of subject id. Example: [1]
     * @bodyParam languages array Array of subject id. Example: [1]
     * @bodyParam grades array Array of subject id. Example: [3]
     * @bodyParam edu_systems array Array of subject id. Example: [2] 
     * @response 200
     *      {
     * "id": 4,
     *"email": "ayamaygdy2@gmail.com",
     *"email_verified_at": null,
     *"created_at": "2020-08-26 15:20:10",
     *"updated_at": "2020-08-26 15:20:10",
     *"full_name": "aya",
     *"type": "t",
     *"active": 1,
     *"birth": "1999-05-05",
     *"gender": "female",
     *"status": 1,
     *"image_url": null,
     *"username": "ayamagdy",
     *"profile": {
     *   "id": 3,
     *   "user_id": 4,
     *   "nationality": null,
     *   "phone": "01059996633",
     *   "postal_code": null,
     *   "exp_years": null,
     *   "exp_desc": null,
     *   "payment_info": null,
     *   "avg_rate": 0,
     *   "month_rate": 0,
     *   "rank": 0,
     *   "rates_count": 0,
     *   "courses": null,
     *   "certifications": null,
     *   "master_degree": null,
     *   "weekly": [
     *       {
     *          "at": [
     *               {
     *                   "time_to": "12:10",
     *                   "time_from": "11:00",
     *                   "started_from": "2020-09-03"
     *               }
     *           ],
     *           "on": "thu"
     *       }
     *   ],
     *   "university_degree_id": null,
     *   "price_info": {
     *       "pending": null
     *   },
     *   "national_id": null,
     *   "phones": null,
     *   "suggested_subjects": [],
     *   "other_subjects": null
     *},
     *"specialist_in": [
     *  {
     *       "id": 1,
     *       "slug": "hello",
     *       "title": "hello",
     *       "created_at": "2020-09-01 12:35:49",
     *       "updated_at": "2020-09-01 12:35:49",
     *       "is_active": 1,
     *       "pivot": {
     *           "user_id": 4,
     *           "subject_id": 1
     *       }
     *   }
     *],
     *"languages": [
     *   {
     *       "id": 1,
     *       "slug": "english",
     *       "title": "english",
     *       "pivot": {
     *           "user_id": 4,
     *           "language_id": 1
     *       }
     *   }
     *],
     *"edu_systems": [
     *   {
     *       "id": 1,
     *       "slug": "maam",
     *       "title": "maamm",
     *       "created_at": "2020-09-01 12:37:19",
     *       "updated_at": "2020-09-01 12:37:19",
     *       "pivot": {
     *           "user_id": 4,
     *           "edu_id": 1
     *       }
     *   }
     *],
     *"latest_reviews": [],
     *"grades": [
     *   {
     *       "id": 2,
     *       "slug": "kg2",
     *       "title": "kg2",
     *      "created_at": "2020-09-09 00:00:00",
     *        "updated_at": "2020-09-09 00:00:00",
     *       "pivot": {
     *           "user_id": 4,
     *           "grade_id": 2
     *       }
     *   }
     *]
     *}
     *   
     * 
     *  
     *   
     *  
     * 
     *
     */
      public function updateSubjects(Request $request)
      {
      
          $user = $this->user->updateTeacherProfile(Auth::id(), $request);
        
          return response()->json($user,200); //Updated
        
      }


}
