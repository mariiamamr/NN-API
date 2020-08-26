<?php
namespace App\Container\Contracts\Calenders;

use Illuminate\Support\Facades\Storage;


class Formater
{
  public function __construct($week_slots, $queued_slots)
  {
    $this->week_slots = $week_slots;
    $this->queued_slots = $queued_slots;
  }

  public function getStudentCalender()
  {
    $key = 1;
    $weekly_slots = $this->week_slots;
    $queued_slots = $this->queued_slots;


    $merged = array_map(function ($item) use ($queued_slots, $key) {
      $dates_a2 = array_column($queued_slots, 'date');

      if (in_array($item['date'], $dates_a2)) {
        $key = array_search($item['date'], $dates_a2);

        if ($key !== false) {
          $has_same_time = collect($item['at'])->where('time_from', $queued_slots[$key]['at']['time_from'])->keys()->first();

          if (!is_null($has_same_time)) {
            $item['at'][$has_same_time] = $queued_slots[$key]['at'];
            return $item;
          }

          $item['at'][] = $queued_slots[$key]['at'];
          return $item;
        }
      }


      return [
        $item['date'] => collect($item['at'])->transform(function ($timeItem) use ($key) {
          return [
            'id' => $key++, 'title' => $timeItem['time_from'] . ' To ' . $timeItem['time_to'],
            'lecture_id' => $timeItem['lecture_id']
          ];
        })->toArray()
      ];
    }, $weekly_slots);

    return (count($merged) > 1) ? call_user_func_array('array_merge', $merged) : $merged;
  }

  public function getTeacherCalender()
  {
    $key = 1;
    $weekly_slots = $this->week_slots;
    $queued_slots = $this->queued_slots;

    $merged_array = array_merge($weekly_slots, $queued_slots);

    return collect($merged_array)->transform(function ($item) use (&$key) {
      $time_start = \Carbon\Carbon::parse($item['date'] . ' ' . $item['time_from']);
      $time_end = \Carbon\Carbon::parse($item['date'] . ' ' . $item['time_to']);
      $now = \Carbon\Carbon::now();
      $should_start = ($time_start->timestamp <= $now->timestamp) && ($time_end->timestamp >= $now->timestamp);


      return array_merge($item, [
        "id" => $key++,
        "date" => date('d M', strtotime($item['date'])),
        "should_start" => $should_start,
        "session_id" => $item['tokbox_session_id'] ?? null,
        "date_time" => $time_start->format('Y-m-d H:i'),
        "image_base_url" => '',
        "users" => $item['users'] ?? null
      ]);
    });
  }


  public function getDates()
  {
    return array_values(array_unique(array_column(array_merge($this->queued_slots, $this->week_slots), 'date')));
  }

  public function getSlots()
  {

    $merged_results = array_merge($this->queued_slots, $this->week_slots);

    return collect($merged_results)->map(function ($item) {
      return [
        "date" => $item['date'],
        "time_from" => $item['time_from'],
        "time_to" => $item['time_to'],
        "lecture_id" => $item['lecture_id'],
      ];
    })->toArray();
  }

  public function getSlotsWithDateInfo($date, $date_format = 'Y-m-d')
  {
    $slots = $this->getSlots();

    return [
      "date" => date($date_format, \strtotime($date)),
      "slots" => $slots
    ];    
  }
}
