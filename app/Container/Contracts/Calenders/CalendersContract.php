<?php
namespace App\Container\Contracts\Calenders;

use App\Models\Schedule;
use App\Models\Lecture;
//use App\Container\Contracts\Services\TokBox\TokBoxContract;
use App\Container\Contracts\UserInfos\WeeklyContract;
use App\Container\Contracts\Payments\PaymentsContract;


interface CalendersContract
{
    public function __construct(
        Schedule $schedule,
        Lecture $lecture,
        //TokBoxContract $tokbox,
        WeeklyContract $weekly_feature,
        PaymentsContract $payment
    );

    public function delete($id);
    
    public function canAddNewSlot($profile, $data);

    public function getPast($teacher_id, $date, $opertaion = '=');

    public function getComing($profile, $date);

    public function getCalender($profile, $date);

}
