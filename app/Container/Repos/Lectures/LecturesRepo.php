<?php
namespace Repos\Lectures;

use Contracts\Lectures\LecturesContract;
use App\Lecture;
use Contracts\Users\UsersContract;
use Contracts\UserInfos\UserInfosContract;
use Contracts\Calenders\CalendersContract;
use Carbon\Carbon;
use Contracts\Services\TokBox\TokBoxContract;
use Contracts\Options\OptionsContract;

class LecturesRepo implements LecturesContract
{
  public function __construct(
    Lecture $lecture,
    UserInfosContract $user_info,
    CalendersContract $calender,
    TokBoxContract $tokbox,
    OptionsContract $option
  ) {
    $this->lecture = $lecture;
    $this->user_info = $user_info;
    $this->calender = $calender;
    $this->tokbox = $tokbox;
    $this->option = $option;
  }

  public function get($id)
  {
    return $this->lecture->findOrFail($id);
  }

  public function getAll()
  {
    return $this->lecture->all();
  }

  public function set($teacher_id, $data)
  {
    $time_duration = $this->option->getValueByName('session_duration');
    $start_at = \Carbon\Carbon::parse($data->date . ' ' . $data->time_from);

    return $this->lecture->create([
      "teacher_id" => $teacher_id,
      "date" => $start_at->format('Y-m-d'),
      "time_from" => $data->time_from,
      "time_to" => date(
        "H:i",
        strtotime('+' . $time_duration . ' minutes', $start_at->timestamp)
      ),
    ]);
  }

  public function update($id, $data)
  {
    $lecture = $this->get($id);

    if (!is_null($lecture->payed_user_id) || !is_null($lecture->checkout_user_id)) {
      return false;
    }
    $time_duration = $this->option->getValueByName('session_duration');
    $start_at = \Carbon\Carbon::parse($data->date . ' ' . $data->time_from);
    return $lecture->update([
      "date" => $start_at->format('Y-m-d'),
      "time_from" => $data->time_from,
      "time_to" => date(
        "H:i",
        strtotime('+' . $time_duration . ' minutes', $start_at->timestamp)
      ),
    ]);
  }

  public function delete($id)
  {
    $lecture = $this->get($id);

    // if (!is_null($lecture->payed_user_id) || !is_null($lecture->checkout_user_id))
    if (!is_null($lecture->payed_user_id)) {
      return false;
    }

    $this->calender->delete($id);
    return $lecture->delete();
  }

  public function paginate($search)
  {
    return $this->lecture->where('teacher_id', $search)->paginate();
  }

  public function getAvaliable($limit)
  {
    return $this->lecture
      ->whereNull('checkout_user_id')
      ->whereNull('payed_user_id')
      ->where('date', '>=', date('Y-m-d'))
      ->orderBy('date', 'ASC')->take($limit)->get();
  }


  public function search($data)
  {
    $query = $this->lecture
      ->where('started', false)
      ->whereNull('checkout_user_id')
      ->whereNull('payed_user_id');

    if (isset($data->lang) && !empty($data->lang)) {
      $query = $query->whereHas('languages', function ($query) use ($data) {
        return $query->where("languages.id", $data->lang);
      });
    }

    if (isset($data->subject) && !empty($data->subject)) {
      $query = $query->whereHas('subjects', function ($query) use ($data) {
        return $query->where('subjects.id', $data->subject);
      });
    }

    if (isset($data->grade) && !empty($data->grade)) {
      $query = $query->whereHas('grades', function ($query) use ($data) {
        return $query->where("grades.id", $data->grade);
      });
    }

    if (isset($data->rate) && !empty($data->rate)) {
      $query = $query->whereHas('teacher', function ($query) use ($data) {
        return $query->whereHas('profile', function ($q) use ($data) {
          return $q->where('avg_rate', ">=", $data->rate);
        });
      });
    }

    return $query->with('teacher')->paginate(1);
  }

  public function teacherAvalibleLecutre($teacher, $from = 'now')
  {
    $lectures = ($teacher->lectures()->exists()) ? $teacher->lectures
      // ->where('checkout_user_id', null)
      // ->where('payed_user_id', null)
      ->where('date', '>=', \Carbon\Carbon::parse($from))
      ->where('date', '<', \Carbon\Carbon::parse($from)->addMonth(1))
      ->transform(function ($item) {
        return [
          'date' => $item->date,
          'time_from' => $item->time_from,
          'time_to' => $item->time_to,
          'lecture_id' => $item->id,
          'teacher_id' => $item->teacher_id,
          'payed_user_id' => $item->payed_user_id,
          "timestamp" => strtotime($item->date . ' ' . $item->time_from)
        ];
      })->toArray() : [];


    $removed_arrays = array_filter($lectures, function ($item, $key) {
      return (!is_null($item['payed_user_id'])) ? true : false;
    }, ARRAY_FILTER_USE_BOTH);

    $removed_timestamps = array_column($removed_arrays, 'timestamp');

    $week_avaliable = (!is_null($teacher->profile) && !is_null($teacher->profile->weekly)) ?
      \getWeekly(json_decode($teacher->profile->weekly), $from) : [];

    $avaliability = array_merge($week_avaliable, $lectures);

    $avaliability = array_values(array_filter(array_map(function ($item) use ($teacher, $removed_timestamps) {
      return (!in_array($item['timestamp'], $removed_timestamps)) ? array_merge($item, [
        "teacher_id" => $teacher->id,
        "payed_user_id" => null
      ]) : null;
    }, $avaliability)));

    return $avaliability;
  }

  public function enrollLectureForUser($user_id, $data)
  {
    if (is_null($data->lecture_id)) {
      $lecture = $this->set($data->teacher_id, $data);
    } else {
      $lecture = $this->get($data->lecture_id);
    }

    if (is_null($lecture->checkout_user_id)) {
      return (new UserEnrolls($lecture))->enroll($user_id, $data);
    }

    return [
      "checkedout" => !is_null($lecture->checkout_user_id),
      "payed" => !is_null($lecture->payed_user_id),
    ];
  }


  public function create_slot($teacher_id, $data)
  {
    $profile = $this->user_info->getByUserID($teacher_id);

    if (!$this->calender->canAddNewSlot($profile, $data)) {
      return false;
    }

    if ($data->weekly) {
      $slot = $this->user_info->setWeekly($profile, $data);
    } else {
      $slot = $this->set($teacher_id, $data);
    }
    
    return $slot;
  }

  public function delete_slot($teacher_id, $data)
  {
    $flag = false;

    if ($data->lecture_id) {
      $flag = $this->delete($data->lecture_id);
    } else {
      $flag = $this->user_info->deleteWeekly($teacher_id, $data);
    }

    return $flag;
  }

  public function update_slot($teacher_id, $data)
  {
    $flag = false;

    $profile = $this->user_info->getByUserID($teacher_id);
    if (!$this->calender->canAddNewSlot($profile, $data)) {
      return false;
    }
    if ($data->lecture_id && !isset($data->new['weekly'])) {
      if ($data->lecture_id) {
        $flag = $this->update($data->lecture_id, (object)$data->new);
      } else {
        $this->user_info->deleteWeekly($teacher_id, (object)$data->old);
        $flag = $this->set($teacher_id, (object)$data->new);
      }
    } elseif ($data->lecture_id && isset($data->new['weekly'])) {
      // $flag = $this->update($data->lecture_id, (object)$data->new);
      $flag = $this->delete($data->lecture_id);
      $flag = $this->user_info->setWeekly($teacher_id, (object)$data->new);
    } else {
      $flag = $this->user_info->updateWeekly($teacher_id, $data);
    }

    return $flag;
  }

  public function getSessionsCurrentAndPast($teacher_id)
  {
    // $profile = $this->lecture->where
  }

  public function startSession($id, $type = 's')
  {
    $lecture = $this->get($id);

    if(!$lecture->tokbox_session_id){
      return null;
    }

    $date_from = Carbon::parse($lecture->date . ' ' . $lecture->time_from);
    $date_to = Carbon::parse($lecture->date . ' ' . $lecture->time_to);

    if ($date_from->timestamp <= time() && $date_to->timestamp >= time()) {
      return [
        "token" => $this->tokbox->generate_token($lecture->tokbox_session_id, [
          // 'role' => ($type == 't') ? \OpenTok\Role::PUBLISHER : \OpenTok\Role::SUBSCRIBER,
          'role' => \OpenTok\Role::PUBLISHER,
          'expireTime' => $date_to->timestamp, // in one week
          // 'data' => 'lecture_id=${$lecture->id}&$user_id=1'
        ]),
        "sessionId" => $lecture->tokbox_session_id,
        "lecture" => $lecture
      ];
    }

    return null;
  }
}
