<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Container\Contracts\Users\UsersContract;
use App\Container\Contracts\Blogs\BlogsContract;
use App\Container\Contracts\Lectures\LecturesContract;
//use App\Container\Contracts\Services\TokBox\TokBoxContract;
//use App\Container\Repos\Services\TokBox\TokBoxRepo;
use App\Container\Contracts\Search\SearchQueryContract;
use App\Models\Subject;
use App\User;
class HomeController extends Controller
{
    public function __construct(/*BlogsContract $blog,*/ UsersContract $teacher, SearchQueryContract $query, Subject $subjects, User $user)
    {
    //   $this->middleware('auth', ['except' => ['index', 'search', 'approvedTeachers']]);
    //   parent::__construct();
     // $this->blog = $blog;
      $this->teacher = $teacher;
      $this->query = $query;
      $this->subjects = $subjects;
      $this->user = $user;
    }

    public function search(Request $request)
  {
    $data = $this->teacher->searchForTeachers($request)->appends(request()->except('page'));

    if ($data->all() == null) {
      $this->query->set($request->all());
      return $data;
    }
    //return parent::view('index', ["data" => $data]);
    return response()->json(['data' => $data], 200);   

  }

  public function approvedTeachers()
  {
    if (request()->filter) {
      $teachers = $this->teacher->approvedFilterdTeachersPaginate(request()->filter);
    } elseif (request()->subject || request()->grade || request()->lang || request()->rate || request()->price || request()->available) {
      $teachers = $this->teacher->searchBannerForTeachers(request());
    } else {
      $teachers = $this->teacher->approvedTeachersPaginate();
    }
    if ($teachers->all() == null) {
      $this->query->set(request()->all());
      return response()->json(['teachers' => $teachers], 200);   
    }
    return response()->json(['teachers' => $teachers], 200);   
}
}
