<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Review;
use App\Models\ReviewStudent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SessionRatingNotification;
use App\Container\Contracts\Users\UsersContract;
//use App\Container\Contracts\SubUsers\SubUsersContract;
use Carbon\Carbon;


class SessionRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:rate';

    use Notifiable;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification for user in case of no rating';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule, UsersContract $user,/* SubUsersContract $sub_user,*/ Review $review, ReviewStudent $review_student)
    {
        parent::__construct();
        $this->schedule = $schedule;
        $this->user = $user;
       // $this->sub_user = $sub_user;
        $this->review = $review;
        $this->review_student = $review_student;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now()->format('H:i:s');
        $sessions = $this->schedule->get()->toArray();
        foreach($sessions as $session){
            if($session['time_to'] > $now){
                $user = $this->user->get($session['user_id']);
                $teacher = $this->user->get($session['teacher_id']);
                $review = $this->review->where('student_id', $session['user_id'])->where('lecture_id', $session['lecture_id'])->get()->toArray();
                $review_student = $this->review_student->where('teacher_id', $session['teacher_id'])->where('lecture_id', $session['lecture_id'])->get()->toArray();
                $message = "Rate your session!";
                if($review == null){
                    Notification::send($user, new SessionRatingNotification($message));
                }
                if(!$review_student == null){
                    Notification::send($teacher, new SessionRatingNotification($message));
                }
            }
        }
    }
}
