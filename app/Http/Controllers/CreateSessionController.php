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
     * @group  Sessions
     * 
     * used to add a new available slot by a teacher. There must be at least 2 hours before the start time.
     *  
     * @authenticated
     * @bodyparam  time_from time required The session's start time in the format hh:mm
     * @bodyparam  date date required The session's date in the format YYYY-MM-DD
     * @bodyparam  weekly boolean required whether the session should be repeated every week or not.
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
public function getPastSessionsForStudents(){
  return $this->user_enroll->getPastSessionForUserWithPaginate(Auth::id());
}
public function getUpcomingSessionsForStudents(){
  return $this->user_enroll->getComingSessionForUserWithPaginate(Auth::id());
}
public function getPastSessionsForTeachers(){
  return $this->user_enroll->getPastSessionForTeacherWithPaginate(Auth::id());
}
public function getUpcomingSessionsForTeachers(){
  return $this->user_enroll->getComingSessionForTeacherWithPaginate(Auth::id());
}
      /**
     * Update an upcoming session
     * @group  Sessions
     * 
     * used by the teacher to edit the details of one of his upcoming sessions. refer to create session for validations.
     *  
     * @authenticated
     * @bodyparam  new JSON required The session's old details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean). Example: {"time_from": "05:00", "date":"2020-12-30", "weekly":"false"}
     * @bodyparam  old JSON required The session's new details: time_from (hh:mm), date (YYYY-MM-DD), and weekly (boolean). Example: {"time_from": "07:00", "date":"2020-12-29", "weekly":"false"}
     * @response {
     *   "started":0,
     *   "teacher_id":17,
     *   "date":"2020-10-16",
     *   "time_from":"09:00:00",
     *   "time_to":"10:10:00",
     *   "created_at":"2020-08-27 19:31:13",
     *   "updated_at:"2020-08-27 19:31:13"
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
  
      return response()->json($slot, 200); 
    }
    
  /**
     * Delete session
     * @group  Sessions
     * 
     * used to delete sessions
     *  
     * @authenticated
     * @bodyparam  lecture_id required Integer
     * @response 200{
     *      "message": "session deleted"
     * }
     * @response 401{
     *      "error": "session can't be deleted"
     * }
     *  @response 404{
     *  "Not Found"
     * }
     */

public function destroy(Request $request)
  {
    $user = User::find(Auth::id());
   if(!$user){
     return response()->json(['errror'=>"Unauthorized"],401);
   }
    $slot = $this->lecture->delete_slot($user->id, (object)[
      "lecture_id" => $request->lecture_id
    ]);
    return response()->json($slot, 200);
  if(!$slot){
    return response()->json(["error"=>"session can't be deleted"], 401);
  }
    return response()->json(["message"=>"session deleted"], 200);
  }
  
    public function enroll(Request $request)
    {
        $result = $this->lecture->enrollLectureForUser(Auth::id(), $request);
        return response()->json(["result" => $result], 200);
    }

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

        return response()->json($result);
    }

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
