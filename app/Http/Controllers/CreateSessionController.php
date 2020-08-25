<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\User; 
use App\UserInfo; 
use App\UniversityDegree;
use Validator;


class CreateSessionController extends Controller
{
    public function createSession(Request $request){
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
        return response()->json(['message'=>"session created"], 200); 
    
    
    
    }

}
