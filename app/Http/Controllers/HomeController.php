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

        /**
     * Search
     * @group  Home
     * 
     * Searches for teachers using Subject, language, grade or all of them. Gets the home page if no ilters are applied.
     *  
     * @bodyParam  lang integer the selected language ID if exists.
     * @bodyParam  subject integer the selected subject ID if exists.
     * @bodyParam  grade integer the selected grade ID if exists.
     * @response {
     * "teachers": {
     *   "current_page": 1,
     *   "data": [
     *       {
     *           "id": 17,
     *           "email": "test.1999@gmail.com",
     *           "email_verified_at": null,
     *           "created_at": "2020-08-27 14:31:25",
     *           "updated_at": "2020-08-27 14:31:25",
     *           "full_name": "wrcefc",
     *           "type": "t",
     *           "active": 1,
     *           "birth": "2000-01-01",
     *           "gender": "female",
     *           "status": 1,
     *           "image_url": null,
     *           "username": "ayakolj",
     *           "profile": {
     *               "id": 1,
     *               "user_id": 17,
     *               "nationality": null,
     *               "phone": null,
     *               "postal_code": null,
     *              "exp_years": null,
     *              "exp_desc": null,
     *              "payment_info": null,
     *               "avg_rate": 3,
     *              "month_rate": 0,
     *               "rank": 0,
     *               "rates_count": 1,
     *               "courses": null,
     *               "certifications": null,
     *               "master_degree": null,
     *               "weekly": null,
     *               "university_degree_id": null,
     *               "price_info": {
     *                   "pending": {
     *                       "individual": 50,
     *                       "group": 100
     *                   }
     *               },
     *               "national_id": null,
     *               "phones": null,
     *               "suggested_subjects": null,
     *               "other_subjects": null
     *           },
     *           "specialist_in": [
     *               {
     *                   "id": 1,
     *                   "slug": "sub",
     *                   "title": "subj_test",
     *                   "created_at": null,
     *                   "updated_at": null,
     *                   "is_active": 1,
     *                   "pivot": {
     *                       "user_id": 17,
     *                      "subject_id": 1
     *                   }
     *              },
     *               {
     *                   "id": 1,
     *                   "slug": "sub",
     *                   "title": "subj_test",
     *                   "created_at": null,
     *                   "updated_at": null,
     *                   "is_active": 1,
     *                   "pivot": {
     *                       "user_id": 17,
     *                       "subject_id": 1
     *                   }
     *               }
     *           ],
     *           "languages": [
     *               {
     *                   "id": 1,
     *                   "slug": "english",
     *                   "title": "english",
     *                   "pivot": {
     *                       "user_id": 17,
     *                       "language_id": 1
     *                   }
     *               }
     *           ],
     *           "grades": [
     *               {
     *                   "id": 1,
     *                   "slug": "kg1",
     *                   "title": "kg1",
     *                   "created_at": "2020-08-31 16:04:27",
     *                   "updated_at": "2020-08-31 16:04:27",
     *                   "pivot": {
     *                       "user_id": 17,
     *                       "grade_id": 1
     *                   }
     *               }
     *           ]
     *      }
     *   ],
     *   "first_page_url": "http://localhost:8000/api/home?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://localhost:8000/api/home?page=1",
     *   "next_page_url": null,
     *   "path": "http://localhost:8000/api/home",
     *   "per_page": "6",
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     * }
     * }    
     */

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
