<?php
namespace App\Container\Contracts\UserInfos;

use App\UserInfo;

interface UserInfosContract
{

  public function __construct(UserInfo $user_info, WeeklyContract $weekly);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function changePassword($id, $data);

  public function mostRatedTeachersMonthly($limit = 3);

  public function getByUserID($teacher_id);

  public function addPayment($user_id, $data);

  public function deletePayment($user_id, $id);

  public function setWeekly($teacher_id, $data);

  public function updateByUserID($user_id, $data);

  public function deleteWeekly($teacher_id, $data);

  public function updateWeekly($teacher_id, $data);
  
  public function changeTeacherPrice($teacher_id, $status);

  public function assignSubjects($teacher_id,$data);
}