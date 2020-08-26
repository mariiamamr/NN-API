<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree;
use Validator;
use App\Container\Contracts\Lectures\LecturesContract;



class CreateSessionController extends Controller
{
    public function __construct( LecturesContract $lecture)
    {
      parent::__construct();
      $this->lecture = $lecture;
    }
  
    public function createSession(Request $request){
        
        if (!Auth::check()) {
            return response()->json(['error' => "unauthenticated"], 401);   
        }

        $user = Auth::user(); 
        if ($user->id->type!='t'){
            return response()->json(['error' => "user must be a teacher"], 401);   
        }
      
        $request->validate([
            'time_from' => 'required',
            'date' => 'required',
            'weekly' => 'boolean',
        ]);
        if ($request->date == date('Y-m-d')) {
            $should_start = \Carbon\Carbon::parse('now')->addHours(2);
            if ($request->time_from <= $should_start->format('H:i')) {
                return response()->json(['error' => "less than two hours left"], 403);   
            }
          }
//
        $slot = $this->lecture->create_slot(\Auth::id(), $request);

        if (!$slot) {
            //can't add new slot in this day
            return response()->json(['error' => "can't add new slot in this day"], 403);   
        }
      
        return response()->json(['message'=>"session created"], 200); 
    }


    public function update(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => "unauthenticated"], 401);   
        }

        $user = Auth::user(); 
        if ($user->id->type!='t'){
            return response()->json(['error' => "user must be a teacher"], 401);   
        }
      
        $request->validate([
            'time_from' => 'required',
            'date' => 'required',
            'weekly' => 'boolean',
        ]);

      if ($request->date == date('Y-m-d')) {
        $should_start = \Carbon\Carbon::parse('now')->addHours(2);
        if ($request->time_from <= $should_start->format('H:i')) {
            return response()->json(['error' => "less than two hours left"], 403);   
        }
      }
      $slot = $this->lecture->update_slot(\Auth::id(), $request);
        
      if(!$slot){
        return response()->json(['error' => "can't add new slot in this day"], 403);   
        }
  
      return response()->json(['message'=>"session updated"], 200); 
    }
    
  

}
