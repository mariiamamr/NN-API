<?php
namespace Contracts\Reports;

use App\Report;
use Contracts\Users\UsersContract;

interface ReportContract
{
    public function __construct(Report $report, UsersContract $user);
    public function set($user_id, $data);

}
