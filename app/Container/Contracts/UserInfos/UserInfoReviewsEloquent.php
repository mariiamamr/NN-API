<?php
namespace App\Container\Contracts\UserInfos;

use App\UserInfo;
use App\ContainerContracts\UserInfos\UserInfoReviewsContract;
use App\Notifications\Teacher\RatingNewTeacher;

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
    
    $user = $profile->users;
    $user->notify(new RatingNewTeacher(
      \App\User::find($data->student_id), 
      \App\Lecture::find($data->lecture_id)));
      
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