<?php
namespace Contracts\Reviews;

use App\Review;
use Contracts\UserInfos\UserInfoReviewsContract;

interface ReviewsContract
{
    public function __construct(Review $review, UserInfoReviewsContract $user);
    public function get($id);
    public function set($student_id, $data);
    public function delete($id);
    public function update($id, $data);
    public function paginate($code = '');

}
