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
