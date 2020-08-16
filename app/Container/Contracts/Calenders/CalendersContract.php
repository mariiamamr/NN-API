<?php
namespace Contracts\Calenders;

use App\Schedule;
use App\Lecture;
use Contracts\Services\TokBox\TokBoxContract;
use Contracts\UserInfos\WeeklyContract;
use Contracts\Payments\PaymentsContract;


interface CalendersContract
{
    public function __construct(
        Schedule $schedule,
        Lecture $lecture,
        TokBoxContract $tokbox,
        WeeklyContract $weekly_feature,
        PaymentsContract $payment
    );

    public function delete($id);
    
    public function canAddNewSlot($profile, $data);

    public function getPast($teacher_id, $date, $opertaion = '=');

    public function getComing($profile, $date);

    public function getCalender($profile, $date);

}
