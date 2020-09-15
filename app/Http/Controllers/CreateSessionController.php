<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree;
use Validator;
use Carbon\Carbon;
use App\Container\Contracts\Lectures\LecturesContract;
use App\Container\Contracts\Lectures\LecturesEloquent;
use App\Container\Contracts\Users\UserEnrollsContract;
use App\Container\Contracts\Users\UserEnrollsEloquent;
use App\Container\Contracts\Calenders\CalendersContract;
use Illuminate\Support\Facades\Auth;
use App\Container\Contracts\Users\UsersContract;


class CreateSessionController extends Controller
{
  protected $lecture;
  protected $calender;
  protected $user;

  public function __construct( UsersContract $user, CalendersContract $calender, LecturesContract $lecture, UserEnrollsContract $user_enroll)
    {
      $this->user_enroll = $user_enroll;
      $this->lecture = $lecture;
      $this->user = $user;
      $this->calender = $calender;
    }
      /**
     * Create a new session
     * used to add a new available slot by a teacher. There must be at least 2 hours before the start time.
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  time_from time required The session's start time in the format hh:mm
     * @bodyParam  date date required The session's date in the format YYYY-MM-DD
     * @bodyParam  weekly boolean required whether the session should be repeated every week or not.
     * @response {
     *      "message": "session created"
     * }
     * @response 401{
     *      "error": "unauthenticated"
     * }
     * @response 401{
     *      "error": "user must be a teacher"
     * }
     * @response 403{
     *      "error": "less than two hours left"
     * }
     * 
     */

    public function createSession(Request $request){
        $user = User::find(Auth::id());
        if (!$user) {
            return response()->json(['error' => "unauthenticated"], 401);   
        }

      
        if ($user->type!='t'){
            return response()->json(['error' => "user must be a teacher"], 401);   
        }
      
        $request->validate([
            'time_from' => 'required',
            'date' => 'required',
            'weekly' => 'boolean',
        ]);
       
        if ($request->date == date('Y-m-d')) {
            $should_start =Carbon::now()->addHours(2);
            if ($request->time_from <= $should_start->format('H:i')) {
                return response()->json(['error' => "less than two hours left"], 403);   
            }
          }
          
//
        $slot = $this->lecture->create_slot($user->id, $request);        

        if (!$slot) {
            //can't add new slot in this day
            return response()->json(['error' => "can't add new slot in this day"], 403);  
        }
      
        return response()->json(['message'=>"session created"], 200); 
    }
     /**
     * get past sessions for students
     * used to get past sessions of the students
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @response 200{
     * "Past sessions": {
     *   "current_page": 1,
     *   "data": [
     *       {
     *           "id": 1,
     *           "user_id": 17,
     *           "lecture_id": 8,
     *           "teacher_id": 17,
     *           "price": 1,
     *           "date": "2020-04-04",
     *           "time_from": "05:30:00",
     *           "time_to": "06:30:00",
     *           "payed": 1,
     *           "sub_user_id": null,
     *           "type": "individual",
     *           "status": "success",
     *           "teachers": {
     *               "id": 17,
     *               "email": "aya_1999_mahmoud@hotmail.com",
     *               "email_verified_at": null,
     *               "created_at": "2020-08-23 22:13:11",
     *               "updated_at": "2020-08-25 19:51:11",
     *               "full_name": "Aya Mahmoud",
     *               "type": "t",
     *               "active": 1,
     *               "birth": "1999-09-09",
     *               "gender": "female",
     *               "status": null,
     *               "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public/users/images/3315277565246255_avatar.jpg",
     *               "username": null
     *           }
     *       }
     *   ],
     *   "first_page_url": "http://localhost:8000/api/getpastsessionsforstudents?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://localhost:8000/api/getpastsessionsforstudents?page=1",
     *   "next_page_url": null,
     *   "path": "http://localhost:8000/api/getpastsessionsforstudents",
     *   "per_page": 15,
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     *    }
     *}
     * 
     */
public function getPastSessionsForStudents(){
  return response()->json(['Past sessions'=>$this->user_enroll->getPastSessionForUserWithPaginate(Auth::id())], 200); 
}
/**
     * get upcoming sessions for students
     * used to get upcoming sessions of the students
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @response 200{
     * "Upcoming sessions": {
     *   "current_page": 1,
     *   "data": [
     *       {
     *           "id": 1,
     *           "user_id": 17,
     *           "lecture_id": 8,
     *           "teacher_id": 17,
     *           "price": 1,
     *           "date": "2020-04-04",
     *           "time_from": "05:30:00",
     *           "time_to": "06:30:00",
     *           "payed": 1,
     *           "sub_user_id": null,
     *           "type": "individual",
     *           "status": "success",
     *           "teachers": {
     *               "id": 17,
     *               "email": "aya_1999_mahmoud@hotmail.com",
     *               "email_verified_at": null,
     *               "created_at": "2020-08-23 22:13:11",
     *               "updated_at": "2020-08-25 19:51:11",
     *               "full_name": "Aya Mahmoud",
     *               "type": "t",
     *               "active": 1,
     *               "birth": "1999-09-09",
     *               "gender": "female",
     *               "status": null,
     *               "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public/users/images/3315277565246255_avatar.jpg",
     *               "username": null
     *           }
     *       }
     *   ],
     *   "first_page_url": "http://localhost:8000/api/getupcomingsessionsforstudents?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://localhost:8000/api/getupcomingsessionsforstudents?page=1",
     *   "next_page_url": null,
     *   "path": "http://localhost:8000/api/getupcomingsessionsforstudents",
     *   "per_page": 15,
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     *    }
     *}
     * 
     */
public function getUpcomingSessionsForStudents(){
  return response()->json(['Upcoming sessions'=>$this->user_enroll->getComingSessionForUserWithPaginate(Auth::id())], 200); 
}
/**
     * get past sessions for students
     * used to get past sessions of the students
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @response 200{
     * "Past sessions": {
     *   "current_page": 1,
     *   "data": [
     *       {
     *           "id": 1,
     *           "user_id": 17,
     *           "lecture_id": 8,
     *           "teacher_id": 17,
     *           "price": 1,
     *           "date": "2020-04-04",
     *           "time_from": "05:30:00",
     *           "time_to": "06:30:00",
     *           "payed": 1,
     *           "sub_user_id": null,
     *           "type": "individual",
     *           "status": "success",
     *           "teachers": {
     *               "id": 17,
     *               "email": "aya_1999_mahmoud@hotmail.com",
     *               "email_verified_at": null,
     *               "created_at": "2020-08-23 22:13:11",
     *               "updated_at": "2020-08-25 19:51:11",
     *               "full_name": "Aya Mahmoud",
     *               "type": "t",
     *               "active": 1,
     *               "birth": "1999-09-09",
     *               "gender": "female",
     *               "status": null,
     *               "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public/users/images/3315277565246255_avatar.jpg",
     *               "username": null
     *           }
     *       }
     *   ],
     *   "first_page_url": "http://localhost:8000/api/getpastsessionsforteachers?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://localhost:8000/api/getpastsessionsforteachers?page=1",
     *   "next_page_url": null,
     *   "path": "http://localhost:8000/api/getpastsessionsforteachers",
     *   "per_page": 15,
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     *    }
     *}
     * 
     */
public function getPastSessionsForTeachers(){
  return response()->json(['Past sessions'=>$this->user_enroll->getPastSessionForTeacherWithPaginate(Auth::id())], 200); 
  
}
/**
     * get upcoming sessions for students
     * used to get upcoming sessions of the students
     * 
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @response 200{
     * "Upcoming sessions": {
     *   "current_page": 1,
     *   "data": [
     *       {
     *           "id": 1,
     *           "user_id": 17,
     *           "lecture_id": 8,
     *           "teacher_id": 17,
     *           "price": 1,
     *           "date": "2020-04-04",
     *           "time_from": "05:30:00",
     *           "time_to": "06:30:00",
     *           "payed": 1,
     *           "sub_user_id": null,
     *           "type": "individual",
     *           "status": "success",
     *           "teachers": {
     *               "id": 17,
     *               "email": "aya_1999_mahmoud@hotmail.com",
     *               "email_verified_at": null,
     *               "created_at": "2020-08-23 22:13:11",
     *               "updated_at": "2020-08-25 19:51:11",
     *               "full_name": "Aya Mahmoud",
     *               "type": "t",
     *               "active": 1,
     *               "birth": "1999-09-09",
     *               "gender": "female",
     *               "status": null,
     *               "image_url": "C:\\Users\\ENG MAHMOUD\\Desktop\\API\\NN-API\\public/users/images/3315277565246255_avatar.jpg",
     *               "username": null
     *           }
     *       }
     *   ],
     *   "first_page_url": "http://localhost:8000/api/getupcomingsessionsforteachers?page=1",
     *   "from": 1,
     *   "last_page": 1,
     *   "last_page_url": "http://localhost:8000/api/getupcomningsessionsforteachers?page=1",
     *   "next_page_url": null,
     *   "path": "http://localhost:8000/api/getupcomingsessionsforteachers",
     *   "per_page": 15,
     *   "prev_page_url": null,
     *   "to": 1,
     *   "total": 1
     *    }
     *}
     * 
     */
public function getUpcomingSessionsForTeachers(){
  return response()->json(['Upcoming sessions'=>  $this->user_enroll->getComingSessionForTeacherWithPaginate(Auth::id())], 200);
}
      /**
     * Update an upcoming session
     * 
     * used by the teacher to edit the details of one of his upcoming sessions. refer to create session for validations.
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  new JSON required The session's old details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean). Example: {"time_from": "05:00", "date":"2020-12-30", "weekly":"false"}
     * @bodyParam  old JSON required The session's new details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean). Example: {"time_from": "07:00", "date":"2020-12-29", "weekly":"false"}
     * @response {
     * "slot": {
     *   "date": "2020-07-06",
     *   "time_from": "01:30",
     *   "time_to": "02:30"
     * }
     * }
     *
     * @response 401{
     *      "error": "unauthenticated"
     * }
     * @response 401{
     *      "error": "user must be a teacher"
     * }
     * @response 403{
     *      "error": "less than two hours left"
     * }
     * 
     */


    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        if (!$user) {
            return response()->json(['error' => "unauthenticated"], 401);   
        }

      
        if ($user->type!='t'){
            return response()->json(['error' => "user must be a teacher"], 401);   
        }
      
     /*   $request->validate([
            'time_from' => 'required',
            'date' => 'required',
            'weekly' => 'required',
            'lecture_id'=>'required'
        ]);*/
      if ($request->date == date('Y-m-d')) {
        $should_start = Carbon::now()->addHours(2);
        if ($request->time_from <= $should_start->format('H:i')) {
            return response()->json(['error' => "less than two hours left"], 403);   
        }
      }
      $slot = $this->lecture->update_slot($user->id, $request);
        
      if(!$slot){
        return response()->json(['error' => "can't add new slot in this day"], 403);   
        }
  
      return response()->json(["slot"=>$slot], 200); 
    }
    
  /**
     * Delete session
     * 
     * used to delete sessions
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  lecture_id required Integer
     * @response {
     *      "message": "session deleted"
     * }
     * @response 401{
     *      "error": "session can't be deleted"
     * }
     * @response 404{
     *       "Not Found"
     * }
     */

public function destroy(Request $request)
  {
    $user = User::find(Auth::id());
   if(!$user){
     return response()->json(['error'=>"Unauthorized"],401);
   }
    $slot = $this->lecture->delete_slot($user->id, (object)[
      "lecture_id" => $request->lecture_id
    ]);
  if(!$slot){
    return response()->json(["error"=>"session can't be deleted"], 401);
  }
    return response()->json(["message"=>"session deleted"], 200);
  }
  /**
     * enroll session
     * 
     * used to enroll sessions for students
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  lecture_id required Integer the lecture the student wants to enroll
     * @bodyParam  teacher_id required Integer the teacher the student want to enroll the lecture with
     * @response 200{
     * "result": {
     *   "checkedout": true,
     *   "payed": true
     * }
     *}
     */
    public function enroll(Request $request)
    {
        $result = $this->lecture->enrollLectureForUser(Auth::id(), $request);
        return response()->json(["result" => $result], 200);
    }


    /**
     * get available days
     * 
     * used to check available days in a certain month and year
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  teacher_id required Integer the id of the teacher
     * @bodyParam  month required Integer
     * @bodyParam  year required Integer
     * @response 200{
     * "result": [
     * {
     *   "date": "2020-07-29",
     *  "badge": false
     * }
     *]
     *}
     */

    public function available_days()
    {
        $id    = (int) request('teacher_id');
        $month = (int) request('month');
        $year  = (int) request('year');

        if ($id <= 0)
            return response()->json([], 404);

        $teacher = $this->user->get($id);

        if (!isset($teacher->profile))
            return response()->json([], 404);

        $month = sprintf("%02d", $month);

        $slots = $this->calender->getDates($teacher->profile, "{$year}-{$month}-01");

        $result = [];

        foreach ($slots as $slot) {
            $result[] = ["date" => $slot, "badge" => false];
        }

        return response()->json(["result" => $result], 200);
    }
    /**
     * get available slots
     * 
     * used to check available slots in a certain date
     * 
     * @group  Sessions
     * 
     *  
     * @authenticated
     * @bodyParam  teacher required Integer the id of the teacher
     * @bodyParam  date required date
     * @response 200{
     * "date": {
     *   "date": "Wednesday, July 29, 2020",
     *   "slots": [
     *      {
     *           "date": "2020-07-29",
     *           "time_from": "02:30",
     *           "time_to": "03:40",
     *           "lecture_id": null
     *       }
     *   ]
     *  },
     *  "slots": [
     *   {
     *       "id": null,
     *       "title": "02:30 To 03:40",
     *       "time_from": "02:30",
     *       "time_to": "03:40",
     *       "date": "2020-07-29"
     *   }
     *    ]
     *}
     */
    public function available_slots()
    {
        $id   = (int) request('teacher');
        $date = request('date');

        if ($id <= 0)
            return response()->json([], 404);

        $teacher = $this->user->get($id);

        if (!isset($teacher->profile))
            return response()->json([], 404);

        $slots  = $this->calender->getDateSlots($teacher->profile, $date, true);
        $result = [];

        foreach ($slots['slots'] as $slot) {
            if ($slot['date'] == request('date')) {
                $result[] = [
                    'id'        => $slot['lecture_id'],
                    'title'     => $slot['time_from'] . ' To ' . $slot['time_to'],
                    'time_from' => $slot['time_from'],
                    'time_to'   => $slot['time_to'],
                    'date'      => request('date')
                ];
            }
        }

        return response()->json([
            'date'  => $slots,
            'slots' => $result
        ]);
    }


}
