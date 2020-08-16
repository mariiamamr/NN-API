<?php
namespace Repos\UserInfos;

use Contracts\UserInfos\WeeklyContract;
use Contracts\Options\OptionsContract;


class WeeklyRepo implements WeeklyContract
{
    public function __construct(OptionsContract $option)
    {
        $this->option = $option;
    }

    public function checkIsAdded($profile, $date, $time_from)
    {
        $week = strtolower(date('D', strtotime($date)));

        // get similar day
        $collection = collect($profile->weekly)->where('on', $week);

        //get similar time
        $get = $collection->pluck('at')->collapse()->filter(function ($item) use ($time_from) {
            return strtotime($time_from) >= strtotime($item['time_from']) && strtotime($time_from) <= strtotime($item['time_to']);
        });


        return $get->count() > 0;
    }

    private function timeDuration($time)
    {
        $time_duration = $this->option->getValueByName('session_duration');
        return date(
            "H:i",
            strtotime('+' . $time_duration . ' minutes', strtotime($time))
        );
    }

    public function set($profile, $data)
    {
        $weekly = (isset($profile->weekly)) ? $profile->weekly : [];

        $collection = collect($weekly)->where('on', strtolower(date('D', strtotime($data->date))));

        if ($collection->count() > 0) {
            $weekly = collect($weekly)->map(function ($item) use ($data) {
                if ($item['on'] == strtolower(date('D', strtotime($data->date)))) {
                    $item['at'][] = [
                        'time_from' => $data->time_from,
                        'time_to' => $this->timeDuration($data->time_from),
                        'started_from' => $data->date
                    ];
                }

                return $item;
            })->toArray();
        } else {
            $weekly[] = array(
                'on' => strtolower(date('D', strtotime($data->date))),
                'at' => [
                    [
                        'time_from' => $data->time_from,
                        'time_to' => $this->timeDuration($data->time_from),
                        'started_from' => $data->date
                    ]
                ]
            );
        }

        $profile->update([
            'weekly' => $weekly
        ]);

        return [
            "date" => $data->date,
            'time_from' => $data->time_from,
            'time_to' => date("H:i", strtotime('+60 minutes', strtotime($data->time_from)))
        ];
    }

    public function delete($profile, $data)
    {
        $weekly = (isset($profile->weekly)) ? $profile->weekly : [];

        $key = collect($weekly)->where('on', strtolower(date('D', strtotime($data->date))))->keys()->first();

        if (!is_null($key)) {
            $time_key = collect($weekly[$key]['at'])->where('time_from', $data->time_from)->keys()->first();

            unset($weekly[$key]['at'][$time_key]);

            if (collect($weekly[$key]['at'])->count() == 0) {
                unset($weekly[$key]);
            }
            return $profile->update([
                'weekly' => array_values($weekly)
            ]);
        }

        return true;
    }

    public function update($profile, $data)
    {
        $weekly = (isset($profile->weekly)) ? $profile->weekly : [];

        if ($data->old['date'] == $data->new['date']) {
            $old_date_key = collect($weekly)->where('on', strtolower(date('D', strtotime($data->old['date']))))->keys()->first();

            if (is_null($old_date_key)) {
                return false;
            }

            $old_time_key = collect($weekly[$old_date_key]['at'])->where('time_from', $data->old['time_from'])->keys()->first();


            if (is_null($old_time_key)) {
                return false;
            }

            $weekly[$old_date_key]['at'][$old_time_key] = [
                "time_from" => $data->new['time_from'],
                "time_to" => $this->timeDuration($data->new['time_from']),
                'started_from' => $data->new['date']
            ];

            return $profile->update([
                'weekly' => array_values($weekly)
            ]);
        } else {
            $this->delete($profile, (object)$data->old);

            return $this->set($profile, (object)$data->new);
        }
    }

    private function seprateTimes($array)
    {
        $res = [];
        foreach ($array as $key => $value) {
            if (isset($value['at'])) {
                foreach ($value['at'] as $item_key => $item_value) {
                    $res[] = array_merge([
                        "date" => ($value['date'] > $item_value['started_from']) ? $value['date'] : $item_value['started_from'],
                        "lecture_id" => $value['lecture_id'],
                    ], $item_value);
                }
            }
        }

        return $res;
    }

    private function avaliableTimePerWeekArray($weekArray, $start_date, $monthlyInterval = false)
    {
        $reformatStartDate = date('Y-m-d', strtotime($start_date));

        $availableTimes = collect($weekArray['at']);

        if ($monthlyInterval) {
            $end_date = \Carbon\Carbon::parse($start_date)->day(1)->addMonth(1)->format('Y-m-d');

            $collection = collect(iterator_to_array(new \DatePeriod(
                \Carbon\Carbon::parse("first " . $weekArray['on'] . " of " . $start_date),
                \Carbon\CarbonInterval::week(),
                \Carbon\Carbon::parse("first " . $weekArray['on'] . " of " . $end_date)
            )));

            return $collection->filter(function ($item) use ($reformatStartDate) {
                return $item >= \Carbon\Carbon::parse($reformatStartDate);
            })->values()->map(function ($item) use ($availableTimes) {
                return [
                    "lecture_id" => null,
                    "date" => $item->format('Y-m-d'),
                    "at" => $availableTimes->where('started_from', '<=', $item->format('Y-m-d'))->toArray()
                ];
            })->filter(function ($item) {
                return count($item['at']) > 0;
            })->values()->toArray();
        }

        $res = [
            "lecture_id" => null,
            "date" => $reformatStartDate,
            "at" => $availableTimes->filter(function ($item) use ($reformatStartDate) {
                return date('m', strtotime($item['started_from'])) <= \Carbon\Carbon::parse($reformatStartDate)->month;
            })->values()->toArray()
        ];

        return (count($res['at']) > 0) ? $res : [];
    }

    public function get($profile, $start_date, $monthlyInterval = false, $seperateSlots = false)
    {

        $weeklySettings = $profile->weekly ?? [];

        if (count($weeklySettings) === 0) {
            return [];
        }

        $weekDayOfStartDate = strtolower(date('D', strtotime($start_date)));

        $weekSettingInThisDay = collect($weeklySettings)->where('on', $weekDayOfStartDate)->first();


        $arr = $this->avaliableTimePerWeekArray($weekSettingInThisDay, $start_date, $monthlyInterval);

        if (count($arr) == 0)
            return [];

        return ($seperateSlots) ? $this->seprateTimes([$arr]) : [$arr];
    }

    private function filterWeekly($weekly, $datesShouldBeRemoved)
    {
        return collect($weekly)->filter(function ($item) use ($datesShouldBeRemoved) {
            return !in_array(\Carbon\Carbon::parse($item['date'] . " " . $item['time_from'])->timestamp, $datesShouldBeRemoved);
        })->values()->toarray();
    }


    public function getMonthly($profile, $start_date, $monthlyInterval = false, $seperateSlots = false, $not_in = [])
    {
        $weeklySettings = $profile->weekly ?? [];

        $arr = [];
        $res = [];
        $search_date = $start_date = date('Y-m-d', strtotime($start_date));

        foreach ($weeklySettings as $key => $value) {
            $count = 0;
            $search_date = $start_date;
            do {
                $count++;

                if ($count > 1 || strtolower(date('D', strtotime($search_date))) != $value['on']) {

                    $search_date = \Carbon\Carbon::parse($search_date)->next(date('w', strtotime($value['on'])));

                    if ($search_date->month !== (int)date('m', strtotime($start_date))) {
                        break;
                    }
                    $search_date = $search_date->format('Y-m-d');
                }
                $res = $this->avaliableTimePerWeekArray($value, $search_date, $monthlyInterval);

                $filtered_results = (count($not_in) > 0) ? $this->filterWeekly($this->seprateTimes([$res]), $not_in) : [];
            } while (count($filtered_results) == 0);


            if ($monthlyInterval) {
                $arr = array_merge($arr, $res);
            } else {
                $arr[] = $res;
            }
        }

        if (count($arr) == 0 || count($arr[0]) == 0)
            return [];

        return ($seperateSlots) ? $this->seprateTimes($arr) : $arr;
    }




    private function getAvaliableTimePerWeekArray($weekArray, $start_date)
    {
        if (!$weekArray) {
            return [];
        }
        
        $reformatStartDate = \Carbon\Carbon::parse($start_date);

        $availableTimes = collect($weekArray['at']);

        $end_date = \Carbon\Carbon::parse($start_date)->day(1)->addMonth(1);

        $collection = collect(iterator_to_array(new \DatePeriod(
            \Carbon\Carbon::parse("first " . $weekArray['on'] . " of " .  $reformatStartDate->format('F')),
            \Carbon\CarbonInterval::week(),
            \Carbon\Carbon::parse("first " . $weekArray['on'] . " of " . $end_date->format('F'))
        )));


        $all = $collection->filter(function ($item) use ($reformatStartDate) {
            return $item >= $reformatStartDate;
        })->values()->map(function ($item) use ($availableTimes) {
            return [
                "lecture_id" => null,
                "date" => $item->format('Y-m-d'),
                "at" => $availableTimes->where('started_from', '<=', $item->format('Y-m-d'))->toArray()
            ];
        })->filter(function ($item) {
            return count($item['at']) > 0;
        })->values()->toArray();

        return $this->seprateTimes($all);
    }

    public function getAll($profile, $start_date, $firstSession = false, $notIn = [])
    {
        $weeklySettings = $profile->weekly ?? [];


        $weekDayOfStartDate = strtolower(date('D', strtotime($start_date)));

        $weekSettingInThisDay = collect($weeklySettings)->where('on', $weekDayOfStartDate)->first();


        $arr = $this->filterWeekly($this->getAvaliableTimePerWeekArray($weekSettingInThisDay, $start_date), $notIn);


        if ($firstSession)
            return (count($arr) > 0) ? [$arr[0]] : [];

        return $arr;
    }

    public function getAllMonthly($profile, $start_date, $firstSession = false, $notIn = [])
    {
        $weeklySettings = $profile->weekly ?? [];

        $arr = [];
        $res = [];
        $search_date = $start_date = date('Y-m-d', strtotime($start_date));

        foreach ($weeklySettings as $key => $value) {
            if(\Carbon\Carbon::parse($start_date)->month  == now()->month){
                if(date('w', now()->timestamp) == date('w', strtotime($value['on']))){
                    $search_date =  now();
                }else{
                    $search_date = now()->next(date('w', strtotime($value['on'])));
                }
            }

            $res = $this->filterWeekly($this->getAvaliableTimePerWeekArray($value, $search_date), $notIn);

            if (count($res) > 0) {
                if (!$firstSession) {
                    $arr = array_merge($arr, $res);
                } else {
                    $arr[] = $res[0];
                }
            }
        }

        return  $arr;
    }
}
