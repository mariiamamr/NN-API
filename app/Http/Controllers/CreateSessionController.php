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
use Illuminate\Support\Facades\Auth;



class CreateSessionController extends Controller
{
    protected $lecture;
    public function __construct( LecturesContract $lecture)
    {
      //parent::__construct();
      $this->lecture = $lecture;
    }
  
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
            return response()->json(['error' => "can't add new slot in this day"], 403);   //????
        }
      
        return response()->json(['message'=>"session created"], 200); 
    }


    public function update(Request $request)
    {
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
            'lecture_id'=>'required'
        ]);

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
    
  



public function destroy(Request $request)
  {
    $user = User::find(Auth::id());

    $slot = $this->lecture->delete_slot($user->id, (object)[
      "date" => (Carbon::parse($request->date_time))->format('Y-m-d'),
      "time_from" => (Carbon::parse($request->date_time))->format('H:i'),
      "lecture_id" => $request->lecture_id
    ]);

    if (!$slot) {
      return response()->json(['error' => "can't add new slot in this day"], 400);
    }

    return response()->json(['message'=>"session deleted"], 200);
  }
  
}