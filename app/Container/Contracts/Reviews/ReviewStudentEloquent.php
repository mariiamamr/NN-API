<?php
namespace App\Container\Contracts\Reviews;

use App\Models\ReviewStudent;
use App\Container\Contracts\Reviews\ReviewStudentContract;

class ReviewStudentEloquent implements ReviewStudentContract
{
    public function __construct(ReviewStudent $review_student)
    {
        $this->review_student = $review_student;
    }
    public function get($id)
    {
        return $this->review_student->findOrFail($id);
    }
    public function set($teacher_id, $data)
    {
        $review_student = $this->review_student->create([
            'content' => $data->content,
            'rate' => $data->rate,
            'teacher_id' => (int) $teacher_id,
            'user_id' => (int) $data->student_id,
            'lecture_id' => (int) $data->lecture_id
        ]);

        return $review_student;
    }
    public function delete($id)
    {

    }
    public function update($id, $data)
    {

    }
    public function paginate($code = '')
    {

    }

}
