<?php
namespace App\Container\Contracts\Reviews;

use App\Models\Review;
use App\UserInfo;
use App\Container\Contracts\Reviews\ReviewsContract;
use App\Container\Contracts\UserInfos\UserInfoReviewsContract;

class ReviewsEloquent implements ReviewsContract
{
    public function __construct(Review $review, UserInfoReviewsContract $user)
    {
        $this->review = $review;
        $this->user = $user;
    }
    public function get($id)
    {
        return $this->review->findOrFail($id);
    }
    public function set($student_id, $data)
    {
        $review = $this->review->create([
            'content' => $data->content,
            'rate' => $data->rate,
            'student_id' => $student_id,
            'user_id' => $data->teacher_id,
            'lecture_id' => $data->lecture_id
        ]);

        $month = $this->review
            ->where('user_id', 1)
            ->where('created_at', '>=', \Carbon\Carbon::parse('last day of ' . \Carbon\Carbon::today()->subMonth(1)->format('M')))
            ->get();

        if($month->count() > 0){
            $month_rate = round($month->sum('rate') / $month->count());
        }
        else{
            $month_rate = 0;
        }

        return $this->user->update($data->teacher_id, (object)[
            'lecture_id'=>$data->lecture_id,
            'student_id' => $student_id,
            'rate' => $data->rate,
            'month_rate' => $month_rate
        ]);
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
