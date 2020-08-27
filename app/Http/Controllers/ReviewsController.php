<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller; 
use App\Container\Contracts\Reviews\ReviewStudentContract;
use App\Container\Contracts\Lectures\LecturesContract;



class ReviewsController extends Controller
{
    protected $review_student;
    protected $lecture;

    public function __construct(ReviewStudentContract $review_student, LecturesContract $lecture)
    {
        $this->review_student = $review_student;
        $this->lecture = $lecture;
    }
    

    public function ratingByTeacher(Request $request)//,$lecture_id)
    {
       // $lecture = $this->lecture->get($lecture_id);

        $request->validate( [
            'content' => 'required',
        ]); 
        $review_student = $this->review_student->set(\Auth::id(), $request);

        return response()->json(['message'=>"rating added"], 200); 
    }

}
