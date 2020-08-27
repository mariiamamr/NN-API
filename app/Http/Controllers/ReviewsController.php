<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller; 
use App\Container\Contracts\Reviews\ReviewStudentContract;
use App\Container\Contracts\Reviews\ReviewsContract;
use App\Container\Contracts\Lectures\LecturesContract;



class ReviewsController extends Controller
{
    protected $review_student;
    protected $lecture;
    protected $review;

    public function __construct(ReviewStudentContract $review_student, LecturesContract $lecture, ReviewsContract $review)
    {
        $this->review_student = $review_student;
        $this->lecture = $lecture;
        $this->review = $review;

    }
    

    public function ratingByTeacher(Request $request)//,$lecture_id)
    {

        $request->validate( [
            'content' => 'required',
        ]); 
        $review_student = $this->review_student->set(\Auth::id(), $request);

        return response()->json(['message'=>"rating added"], 200); 
    }

    public function ratingByStudent(Request $request)
    {
        $request->validate( [
            'content' => 'required',
        ]); 
       
        $review = $this->review->set(\Auth::id(), $request);

        return response()->json(['message'=>"rating added"], 200); 
    }

}
