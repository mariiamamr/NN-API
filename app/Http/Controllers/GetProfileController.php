<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Container\Contracts\Subjects\SubjectsContract;
use App\Container\Contracts\Languages\LanguagesContract;
use App\Container\Contracts\Grades\GradesContract;
use App\Container\Contracts\EduSystems\EduSystemsContract;
use App\Container\Contracts\UniversityDegrees\UniversityDegreesContract;
use App\Container\Contracts\Certificates\CertificatesContract;
use App\Container\Contracts\Prices\PricesContract;
use App\Container\Contracts\Users\UsersContract;


use Illuminate\Support\Facades\Auth;
use App\User;
use App\UserInfo;



class GetProfileController extends Controller
{
    protected $subject;
    protected $grade;
    protected $language;
    protected $edu_system;
    protected $uni_degree;
    protected $certificates;
    protected $price;
    protected $user;


    public function __construct(UsersContract $user,
                                SubjectsContract $subject,
                                LanguagesContract $language,
                                GradesContract $grade,
                                EduSystemsContract $edu_system,
                                UniversityDegreesContract $uni_degree,
                                CertificatesContract $certificates,
                                PricesContract $price)
    {
      $this->subject = $subject;
      $this->language= $language;
      $this->grade=$grade;
      $this->edu_system=$edu_system;
      $this->uni_degree=$uni_degree;
      $this->price=$price;
      $this->user=$user;
      $this->certificates=$certificates;


    }
    /**
     * Get user profile
     * @group  getters
     * 
     * used to get the user's profile.
     *  
     * @response 200{
     *"user": {
     *  "id": 17,
     *   "email": "ayaelsac.1999@gmail.com",
     *   "email_verified_at": null,
     *   "created_at": "2020-08-27 14:31:25",
     *   "updated_at": "2020-08-27 14:31:25",
     *   "full_name": "wrcefc",
     *   "type": "t",
     *   "active": 1,
     *   "birth": "2000-01-01",
     *   "gender": "female",
     *   "status": 1,
     *   "image_url": null,
     *   "username": "ayakolj",
     *   "profile": {
     *       "id": 1,
     *       "user_id": 17,
     *       "nationality": null,
     *       "phone": null,
     *       "postal_code": null,
     *       "exp_years": null,
     *      "exp_desc": null,
     *       "payment_info": null,
     *       "avg_rate": 3,
     *       "month_rate": 0,
     *       "rank": 0,
     *       "rates_count": 1,
     *       "courses": null,
     *       "certifications": null,
     *       "master_degree": null,
     *       "weekly": [
     *           {
     *               "on": "mon",
     *               "at": [
     *                   {
     *                       "time_from": "01:30",
     *                       "time_to": "02:40",
     *                       "started_from": "2020-07-06"
     *                  }
     *              ]
     *          }
     *       ],
     *       "university_degree_id": null,
     *       "price_info": {
     *           "pending": {
     *               "individual": 50,
     *               "group": 100
     *           }
     *       },
     *      "national_id": null,
     *      "phones": null,
     *       "suggested_subjects": null,
     *       "other_subjects": null
     *   },
     *   "languages": [
     *       {
     *           "id": 1,
     *           "slug": "english",
     *           "title": "english",
     *           "pivot": {
     *               "user_id": 17,
     *               "language_id": 1
     *           }
     *       }
     *   ],
     *   "specialist_in": [
     *       {
     *           "id": 1,
     *           "slug": "sub",
     *           "title": "subj_test",
     *           "created_at": null,
     *           "updated_at": null,
     *           "is_active": 1,
     *           "pivot": {
     *               "user_id": 17,
     *               "subject_id": 1
      *          }
      *      },
     *       {
     *           "id": 1,
     *           "slug": "sub",
     *          "title": "subj_test",
     *           "created_at": null,
     *           "updated_at": null,
     *           "is_active": 1,
     *           "pivot": {
     *               "user_id": 17,
     *               "subject_id": 1
     *           }
     *       }
     *   ],
     *   "grades": [
     *       {
     *           "id": 1,
     *           "slug": "kg1",
     *           "title": "kg1",
     *           "created_at": "2020-08-31 16:04:27",
     *          "updated_at": "2020-08-31 16:04:27",
     *           "pivot": {
     *               "user_id": 17,
     *               "grade_id": 1
     *           }
     *       }
     *  ]
     *},
     *"profile": {
     *   "id": 1,
      *  "user_id": 17,
     *   "nationality": null,
     *   "phone": null,
     *   "postal_code": null,
     *   "exp_years": null,
     *   "exp_desc": null,
     *   "payment_info": null,
      *  "avg_rate": 3,
      *  "month_rate": 0,
      *  "rank": 0,
      *  "rates_count": 1,
      *  "courses": null,
      *  "certifications": null,
      *  "master_degree": null,
      *  "weekly": [
      *      {
      *          "on": "mon",
      *          "at": [
      *              {
      *                  "time_from": "01:30",
      *                  "time_to": "02:40",
      *                  "started_from": "2020-07-06"
      *              }
     *           ]
     *       }
     *   ],
     *   "university_degree_id": null,
     *   "price_info": {
     *       "pending": {
     *           "individual": 50,
     *           "group": 100
     *       }
     *   },
     *   "national_id": null,
     *   "phones": null,
     *   "suggested_subjects": null,
     *   "other_subjects": null
     * },
     *"uni_degrees": [],
     *"allSubjects": [
     *   {
     *       "id": 1, 
     *       "title": "subj_test"
     *   },
     *   {
     *       "id": 10,
     *       "title": "subj_test"
     *   }
     * ],
     * "langs": [
     *   {
     *       "id": 1,
     *       "title": "english"
     *   }
     * ],
     * "edus": [],
     * "certificates": [],
     *  "price_config": 50,
     *   "user_certifications": null,
     *   "payment_info": null,
     *   "profilePower": 10,
     *   "other_subjects": null,
     *   "grades": [
     *  {
     *       "id": 1,
     *       "title": "kg1"
     *   }
     *]
     *   }
     */
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
     * Get all subject
     * @group  getters
     * 
     * used to get subjects
     *  
     * @response 200{
    *"Subjects": [
    *  {
    *        "id": 1,
    *        "slug": "hello",
    *        "title": "hello",
    *        "created_at": "2020-09-01 12:35:49",
    *        "updated_at": "2020-09-01 12:35:49",
    *        "is_active": 1
    *    },
    *    {
    *        "id": 2,
    *        "slug": "maths",
    *        "title": "maths",
    *        "created_at": "2020-09-09 00:00:00",
    *        "updated_at": "2020-09-09 00:00:00",
    *        "is_active": 1
    *    }
    *]
    *}
     * @response 401{
     *      "error" : 'Unauthorized'
     * }
     * 
     */

public function getSubjects(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $subjects=$this->subject->getAll();
    return response()->json(['Subjects'=>$subjects], 200); 
}

/**
     * Get all languages
     * @group  getters
     * 
     * used to get languages
     *  
     * @response 200{
     * "Languages": [
     *   {
     *       "id": 1,
     *       "slug": "english",
     *       "title": "english"
     *   }
     * ]
     * }
     * 
     */
public function getLanguages(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $languages=$this->language->getAll();
    return response()->json(['Languages'=>$languages], 200); 
}

/**
     * Get all edusystems
     * @group  getters
     * 
     * used to get edusystems
     *  
     * @response 200{
     *"EduSystems": [
     *   {
     *       "id": 1,
     *       "slug": "maam",
     *       "title": "maamm",
     *       "created_at": "2020-09-01 12:37:19",
     *       "updated_at": "2020-09-01 12:37:19"
     *   }
     *]
     *}
     * 
     */
public function getEdusystems(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $edu_systems=$this->edu_system->getAll();
    return response()->json(['EduSystems'=>$edu_systems], 200); 
}

/**
     * Get all grades
     * @group  getters
     * 
     * used to get grades
     *  
     * @response 200{
     * "Grades": [
     *   {
     *       "id": 1,
     *       "slug": "kg1",
     *       "title": "kg1",
     *       "created_at": "2020-09-01 12:37:48",
     *       "updated_at": "2020-09-01 12:37:48"
     *   }
     *]
     *}
     * 
     */
public function getGrades(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $grades=$this->grade->getAll();
    return response()->json(['Grades'=>$grades], 200); 
}

//Get All Grades
public function getUserProfile(){

    $user = $this->user->getWith(Auth::id(), ['profile', 'languages', 'specialist_in', 'grades']);

    // profile power
    $profilePowerItems = collect($user->profile->only(['nationality', 'phone', 'exp_years', 'exp_desc', 'payment_info', 'courses', 'certifications', 'master_degree', 'university_degree_id', 'price_info']))->map(function ($item) {
        return $item ? 1 : 0;
    });

    $profilePower = intval(($profilePowerItems->sum() / $profilePowerItems->count()) * 100);
    
    $profile=$this->getProfile($user);
    return response()->json([
        "user" => $user,
        "profile" => $user->profile,
        'uni_degrees' => $this->uni_degree->getList(['id', 'title']),
        'allSubjects' => $this->subject->getList(['id', 'title']),
        'langs' => $this->language->getList(['id', 'title']),
        'edus' => $this->edu_system->getList(['id', 'title']),
        'certificates' => $this->certificates->getList(['id', 'label', 'is_required']),
        'price_config' => json_decode($this->price->get()->value),
        'user_certifications' => isset($user->profile->certifications) ? json_decode($user->profile->certifications, 'Array') : null,
        'payment_info' => isset($user->profile->payment_info) ? json_decode($user->profile->payment_info, 'Array') : null,
        'profilePower' => $profilePower,
        'other_subjects' => isset($user->profile->other_subjects) ? json_decode($user->profile->other_subjects, 'Array') : null,
        'grades' => $this->grade->getList(['id', 'title']),

], 200); 
}


}
