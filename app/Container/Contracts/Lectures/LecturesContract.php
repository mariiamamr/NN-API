<?php

namespace Contracts\Lectures;

use App\Lecture;
use Contracts\UserInfos\UserInfosContract;
use Contracts\Calenders\CalendersContract;
use Contracts\Services\TokBox\TokBoxContract;
use Contracts\Options\OptionsContract;

interface LecturesContract
{
  public function __construct(
    Lecture $lecture, 
    UserInfosContract $user_info, 
    CalendersContract $calender,
    TokBoxContract $tokbox,
    OptionsContract $option);

  public function get($id);

  public function getAll();

  public function set($teacher_id, $data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getAvaliable($limit);

  public function search($data);

  public function teacherAvalibleLecutre($teacher, $from);

  public function startSession($id);
}