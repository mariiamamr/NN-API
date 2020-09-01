<?php
namespace  App\Container\Contracts\Reports;

use App\Models\Report;
use App\Container\Contracts\Users\UsersContract;

interface ReportContract
{
    public function __construct(Report $report, UsersContract $user);
    public function set($user_id, $data);

}
