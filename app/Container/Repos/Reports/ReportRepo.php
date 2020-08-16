<?php
namespace Repos\Reports;

use App\Report;
use Contracts\Reports\ReportContract;
use App\Notifications\ReportNotification;
use Illuminate\Support\Facades\Notification;
use Contracts\Users\UsersContract;



class ReportRepo implements ReportContract
{
    private $number_of_pages = 1;
    public function __construct(Report $report, UsersContract $user)
    {
        $this->report = $report;
        $this->user = $user;
    }

    public function set($user_id, $data)
    {
        $report = $this->report->create([
            'email' => $data->email,
            'content' => $data->content,
            'user_id' => $user_id,
            'teacher_id' => $data->teacher_id
        ]);

        $user = $this->user->get($user_id);
        $message = "Your report has been submitted successfully";
        Notification::send($user, new ReportNotification($message));

        return $report;
    }
}
