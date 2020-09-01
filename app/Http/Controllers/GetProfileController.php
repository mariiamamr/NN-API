<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Container\Contracts\Subjects\SubjectsContract;
use App\Container\Contracts\Languages\LanguagesContract;
use App\Container\Contracts\Grades\GradesContract;
use App\Container\Contracts\EduSystems\EduSystemsContract;
use Illuminate\Support\Facades\Auth;
use App\User;

class GetProfileController extends Controller
{
    protected $subject;
    protected $grade;
    protected $language;
    protected $edu_system;
    public function __construct(SubjectsContract $subject,LanguagesContract $language,GradesContract $grade,
    EduSystemsContract $edu_system )
    {
      $this->subject = $subject;
      $this->language= $language;
      $this->grade=$grade;
      $this->edu_system=$edu_system;

    }

//Get All Subjects

public function getSubjects(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $subjects=$this->subject->getAll();
    return response()->json(['Subjects'=>$subjects], 200); 
}

//Get All Languages
public function getLanguages(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $languages=$this->language->getAll();
    return response()->json(['Languages'=>$languages], 200); 
}

//Get All EduSystems
public function getEdusystems(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $edu_systems=$this->edu_system->getAll();
    return response()->json(['EduSystems'=>$edu_systems], 200); 
}

//Get All Grades
public function getGrades(){
    $user = User::find(Auth::id());
    if(!$user){
        return response()->json(['error'=>'Unauthorized'], 401);        //unauthorized    
    }
    
    $grades=$this->grade->getAll();
    return response()->json(['Grades'=>$grades], 200); 
}


}
