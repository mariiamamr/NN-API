<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 5/17/2018
 * Time: 11:30 AM
 */

namespace Contracts\WithDraws;


use App\Schedule;
use App\Withdraw;
use Contracts\Options\OptionsContract;

interface WithDrawsContract
{
    public function __construct(
        Schedule $schedule,
        Withdraw $withdraw,
        OptionsContract $option
    );

    public function get($id);
    public function getAvailableWithdarw($teacher_id);
    public function requestWithdarw($teacher_id);
    public function changeStatus($id, $status);
    public function getRequestedByUserIds($user_ids);   

}