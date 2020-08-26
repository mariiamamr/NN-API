<?php

namespace App\Container\Contracts\UserInfos;

use App\UserInfo;
use App\Container\Contracts\Options\OptionsContract;

interface WeeklyContract
{

    public function __construct(OptionsContract $option);
    public function set($profile, $data);
    public function delete($profile, $data);
    public function update($profile, $data);
    public function get($profile, $start_date);
    public function checkIsAdded($profile, $date, $time_from);
    public function getAll($profile, $start_date, $firstSession = false);
    public function getAllMonthly($profile, $start_date, $firstSession = false, $notIn = []);
}
