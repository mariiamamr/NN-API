<?php
namespace App\Container\Contracts\Reviews;

use App\Models\Review;
use App\Container\Contracts\UserInfos\UserInfoReviewsContract;

interface ReviewsContract
{
    public function __construct(Review $review, UserInfoReviewsContract $user);
    public function get($id);
    public function set($student_id, $data);
    public function delete($id);
    public function update($id, $data);
    public function paginate($code = '');

}
