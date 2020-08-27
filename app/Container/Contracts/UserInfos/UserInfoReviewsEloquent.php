<?php
namespace App\Container\Contracts\UserInfos;

use App\UserInfo;
use App\Container\Contracts\UserInfos\UserInfoReviewsContract;
use App\Notifications\RatingNewTeacher;

use App\User;
use App\Models\Lecture;


class UserInfoReviewsEloquent implements UserInfoReviewsContract
{
  public function __construct(UserInfo $profile)
  {
    $this->profile = $profile;
  }
  public function update($teacher_id, $data)
  {
    $profile = $this->profile->where('user_id', $teacher_id)->first();
    
    $rates_count = $profile->rates_count  + 1;
    $avg_rate = ceil(($profile->avg_rate + $data->rate) / $rates_count);
    $month_rate = $data->month_rate;
    
    // $user = $profile->users;
    $user =User::find( $profile->user_id);

    $user->notify(new RatingNewTeacher(
      User::find($data->student_id), 
      Lecture::find($data->lecture_id)));
      
    return $profile->update([
      "rates_count" => $rates_count,
      "avg_rate" => $avg_rate,
      "month_rate" => $month_rate
    ]);
  }
  public function monthly_review($data)
  {

  }
}