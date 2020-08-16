<?php
namespace Contracts\Reviews;

use App\ReviewStudent;

interface ReviewStudentContract
{
    public function __construct(ReviewStudent $review_student);
    public function get($id);
    public function set($teacher_id, $data);
    public function delete($id);
    public function update($id, $data);
    public function paginate($code = '');

}
