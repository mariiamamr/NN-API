<?php

namespace App\Container\Contracts\Calenders;

use App\Container\Contracts\Calenders\CalendersContract;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Lecture;
use Carbon\CarbonInterval;
use App\Container\Contracts\Calenders\Formater;
//use Contracts\Services\TokBox\TokBoxContract;
use App\Container\Contracts\UserInfos\WeeklyContract;
use App\Container\Contracts\Payments\PaymentsContract;



class CalendersEloquent implements CalendersContract
{
    public function __construct(
        Schedule $schedule,
        Lecture $lecture,
        //TokBoxContract $tokbox,
        WeeklyContract $weekly_feature,
        PaymentsContract $payment
    ) {
        $this->schedule = $schedule;
        $this->lecture = $lecture;
        //$this->tokbox = $tokbox;
        $this->weekly_feature = $weekly_feature;
        $this->payment = $payment;
    }

    public function delete($id)
    {
        return $this->schedule->where('payed', false)->where('lecture_id', $id)->delete();
    }

    private function checkScheduledSlots($teacher_id, $date, $time_from)
    {
        $collection = $this->schedule
            ->where('teacher_id', $teacher_id)
            ->where('date', $date)
            ->get()
            ->unique('time_from');

        $collection->each(function ($item, $key) use ($time_from) {
            if ($item->time_form > $time_from && $item->time_to < $item_form) {
                return false;
            }
        });

        return $collection->count() == 0;
    }

    private function checkQueuedSlots($teacher_id, $date, $time_from)
    {
        $collection = $this->lecture
            ->where('teacher_id', $teacher_id)
            ->where('date', $date)
            ->get();

        // get item within range
        $sameRangeArray = $collection->map(function ($item, $key) use ($time_from) {
            if (
                date("H:i", strtotime($item->time_from)) <= $time_from &&
                date("H:i", strtotime($item->time_to)) >= $time_from
            ) {
                return $item;
            }

            return null;
        })->filter()->values();

        return $sameRangeArray->count() > 0;
    }

    public function canAddNewSlot($profile, $data)
    {
        $in_queue = $this->checkQueuedSlots($profile->user_id, $data->date, $data->time_from);

        $in_weekly = $this->weekly_feature->checkIsAdded($profile, $data->date, $data->time_from);

        if ($data->weekly) {
            return !$in_weekly;
        }

        return (!$in_queue && !$in_weekly);
    }

    private function getQueuedSlots($teacher_id, $date, $operator = '=', $monthlyResult = false)
    {
        $query = $this->lecture
            ->where('teacher_id', $teacher_id)
            // ->where('date', $opertaion, Carbon::parse($date)->format('Y-m-d'))
            // ->where('time_to',$opertaion, now()->format('H:i'));
            ->whereRaw('CONCAT(`date`," ",`time_to`) ' . $operator . '"' . \Carbon\Carbon::parse($date)->format("Y-m-d H:i:s") . '"');


        if ($monthlyResult) {
            $query = $query->where('date', '<', Carbon::parse($date)->day(1)->addMonth()->format('Y-m-d'));
        }

        return $query->get();
    }

    private function getPaidUnpaidSlots($teacher_id, $date, $opertaion = '=', $monthlyResult = false)
    {
        $queued_slots = $this->getQueuedSlots($teacher_id, $date, $opertaion, $monthlyResult);

        $paid = $queued_slots->filter(function ($item) {
            return !(is_null($item->payed_user_id));
        })->values();

        $unpaid = $queued_slots->filter(function ($item) {
            return (is_null($item->payed_user_id)) && (is_null($item->checkout_user_id));
        })->values();

        $all = $queued_slots;

        return [
            'paid' => $paid,
            'unpaid' => $unpaid,
            'all' => $all
        ];
    }

    public function getCalender($profile, $date)
    {
        $queued_slots = $this->getQueuedSlots($profile->user_id, $date, '>=', true);

        $weekly_slots = ($profile->weekly) ?
            $this->getWeeklyInterval(
                $profile->weekly,
                $date,
                '>='
            ) : [];

        // echo time().'<br/>';
        return (new Formater($weekly_slots, $queued_slots))->getStudentCalender();
    }

    public function getPast($teacher_id, $date, $operator = '<=')
    {
        $lectures = $this->schedule
            ->where('teacher_id', $teacher_id)
            // ->where('date', $opertaion, Carbon::parse($date)->format('Y-m-d'))
            // ->where('time_to', $opertaion, Carbon::parse($date)->format('H:i'))
            ->whereRaw('CONCAT(`date`," ",`time_to`) ' . $operator . '"' . \Carbon\Carbon::parse($date)->format("Y-m-d H:i:s") . '"')
            ->where('payed', true)
            ->with('teacherReviews')
            ->get();

        return (new Formater([], $this->reformatQueuedSlots($lectures)))->getTeacherCalender();
    }

    private function getTimestamps($array)
    {
        return collect($array)->map(function ($item) {
            return Carbon::parse($item['date'] . " " . $item['time_from'])->timestamp;
        })->toArray();
    }

    private function filterWeekly($weekly, $datesShouldBeRemoved)
    {
        return collect($weekly)->filter(function ($item) use ($datesShouldBeRemoved) {
            return !in_array(Carbon::parse($item['date'] . " " . $item['time_from'])->timestamp, $datesShouldBeRemoved);
        })->values()->toarray();
    }

    public function getComing($profile, $date)
    {
        $queued_slots = $this->getPaidUnpaidSlots($profile->user_id, $date, '>=', true);

        $weekly_slots =
            $this->weekly_feature->getAllMonthly($profile, $date, true, $this->getTimestamps($queued_slots['all']));
            
        return (new Formater(
            $weekly_slots,
            $this->reformatQueuedSlots($queued_slots['all'])
        ))->getTeacherCalender();
    }

    private function reformatQueuedSlots($result)
    {
        if ($result->count() == 0)
            return [];
        return $result->map(function ($item) {
            $reviews = $item->toArray()['teacher_reviews'] ?? [];

            return [
                "date" => Carbon::parse($item->date)->format('Y-m-d'),
                "time_from" => date('H:i', strtotime($item->time_from)),
                "time_to" => date('H:i', strtotime($item->time_to)),
                "lecture_id" => $item->id,
                "student_id" => $item->user_id ?? null,
                "teacherReviews" => isset($reviews) && is_array($reviews) && count($reviews) > 0 ? $reviews : null,
                "users" => (!is_null($item->payed_user_id) || $item->payed) ? array_merge(
                    $item->attendees->pluck('image_url')->all(),
                    $item->sub_attendees->pluck('image_url')->all()
                ) : null,
                "timestamp" => Carbon::parse($item->date . " " . $item->time_from)->timestamp,
            ];
        })->toArray();
    }

    public function getDates($profile, $date)
    {
        if(\Carbon\Carbon::parse($date)->month  == now()->month){
            $date = now();
        }
        $queued_slots =
            $this->getPaidUnpaidSlots($profile->user_id, $date, '>=', true);

        $weekly_slots =
            $this->weekly_feature->getAllMonthly($profile, $date, false, $this->getTimestamps($queued_slots['all']));

        return (new Formater($weekly_slots, $this->reformatQueuedSlots($queued_slots['unpaid'])))->getDates();
    }

    public function getDateSlots($profile, $date, $withdateInfo = false)
    {
        // bug fix -> needs to be refactored
        if (is_null($profile))
            return null;

        $queued_slots =
            $this->getPaidUnpaidSlots($profile ? $profile->user_id : null, $date, '>=', true);

        $weekly_slots =
            $this->weekly_feature->getAll($profile, $date, true, $this->getTimestamps($queued_slots['all']));

        $formater = (new Formater($weekly_slots, $this->reformatQueuedSlots($queued_slots['unpaid'])));

        return (!$withdateInfo) ? $formater->getSlots() : $formater->getSlotsWithDateInfo($date, 'l, F d, Y');
        
    }
}
