<?php
namespace App\Container\Contracts\UserInfos;

use App\Container\Contracts\UserInfos\UserInfosContract;
use App\UserInfo;
use Carbon\Carbon;
//use function GuzzleHttp\json_encode;
use App\Models\Schedule;
//use Contracts\Weekly\Weekly;
use App\ContainerContracts\UserInfos\WeeklyContract;

class UserInfosEloquent implements UserInfosContract
{
  public function __construct(UserInfo $user_info, WeeklyContract $weekly)
  {
    $this->profile = $user_info;
    $this->weekly = $weekly;
  }

  public function get($id)
  { }

  public function getAll()
  { }

  public function set($data)
  { }

  public function update($id, $data)
  { }

  public function delete($id)
  { }

  public function paginate($search)
  { }

  public function changePassword($id, $data)
  { }
  public function mostRatedTeachersMonthly($limit = 3)
  {
    return $this->profile->orderBy('month_rate', 'DESC')->take($limit)->get();
  }

  public function getByUserID($teacher_id)
  {
    // return $this->profile->where('user_id', $teacher_id)->get();
    return $this->profile->firstOrCreate([
      'user_id' => $teacher_id
    ]);
  }

  /**
   * insert- update- delete payment
   */
  public function addPayment($user_id, $data)
  {
    $profile = $this->getByUserID($user_id);
    return (new CreditCardFeature($profile))->set($data);
  }

  public function deletePayment($user_id, $id)
  {
    $profile = $this->getByUserID($user_id);
    return (new CreditCardFeature($profile))->delete($id);
  }

  public function updatePayment($user_id, $id)
  {
    $profile = $this->getByUserID($user_id);
    return (new CreditCardFeature($profile))->update($id);
  }

  public function updateByUserID($user_id, $data, $status = 0)
  {
    $sorted_data = ($status) ? $data->except('price_info', 'certifications', 'payment_info', 'other_subjects') : $data->except('certifications', 'payment_info', 'other_subjects');
    $profile = $this->getByUserID($user_id)->fill((array)$sorted_data);
    if ($status) {
      $price = $profile->price_info;
      $price['pending'] = $data->price_info;
      $profile->price_info = $price;
    }

    $profile->save();
    return $profile;
  }

  public function changeTeacherPrice($teacher_id, $status)
  {
    $profile = $this->getByUserID($teacher_id);

    if ($status) {
      $pending_price = $profile->price_info['pending'];
      $profile->price_info = $pending_price;

      Schedule::where('teacher_id', $teacher_id)
        ->whereNull('payed')
        ->whereNull('sub_user_id')
        ->update(['price' => $pending_price['individual']]);

      Schedule::where('teacher_id', $teacher_id)
        ->whereNull('payed')
        ->where('sub_user_id', '!=', null)
        ->update(['price' => $pending_price['group']]);
    } else {
      $profile->price_info->unset('pending');
    }
    return $profile->save();
  }

  /**
   * insert- update- delete weekly settings
   */

  public function setWeekly($teacher_id, $data)
  {
    $profile = (!is_object($teacher_id)) ? $this->getByUserID($teacher_id) : $teacher_id;

    return $this->weekly->set($profile, $data);
  }

  public function deleteWeekly($teacher_id, $data)
  {
    $profile = (!is_object($teacher_id)) ? $this->getByUserID($teacher_id) : $teacher_id;

    return $this->weekly->delete($profile, $data);
  }

  public function updateWeekly($teacher_id, $data)
  {
    $profile = $this->getByUserID($teacher_id);
    
    return $this->weekly->update($profile, $data);
  }

  public function checkWeeklyAvailablity()
  { }

  public function assignSubjects($teacher_id, $data)
  {
    $user_info = $this->getByUserID($teacher_id);

    if (count($user_info->suggested_subjects) !== count($data->subjects)) {
      return null;
    }
    
    $old_subjects = $user_info->users->specialist_in()->pluck('subject_id')->toArray();

    $user_info->users->specialist_in()->sync(array_merge($old_subjects, $data->subjects));
    $user_info->suggested_subjects = null;
    return $user_info->save();
  }
}
