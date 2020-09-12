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
      /**
     * Rating by teacher
     * @group  Ratings
     * 
     * Used by the teacher to rate a student.
     *  
     * @authenticated
     * @bodyParam  lecture_id integer the ID of the lecture about which the student will be rated.
     * @bodyParam  student_id integer the ID of the student to be rated.
     * @bodyParam  rate integer the rating value.
     * @bodyParam  content string rating message/ comments.
     * @response {
     *  "message": "rating added"
     *  }
     */
    

    public function ratingByTeacher(Request $request)//,$lecture_id)
    {

        $request->validate( [
            'content' => 'required',
        ]); 
        $review_student = $this->review_student->set(\Auth::id(), $request);

        return response()->json(['message'=>"rating added"], 200); 
    }
      /**
     * Rating by student
     * @group  Ratings
     * 
     * Used by the student to rate a teacher.
     *  
     * @authenticated
     * @bodyParam  lecture_id integer the ID of the lecture about which the teacher will be rated.
     * @bodyParam  teacher_id integer the ID of the teacher to be rated.
     * @bodyParam  rate integer the rating value.
     * @bodyParam  content string rating message/ comments.
     * @response {
     *  "message": "rating added"
     *  }
     */

    public function ratingByStudent(Request $request)
    {
        $request->validate( [
            'content' => 'required',
        ]); 
       
        $review = $this->review->set(\Auth::id(), $request);

        return response()->json(['message'=>"rating added"], 200); 
    }

}
