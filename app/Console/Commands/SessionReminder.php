<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SessionReminderNotification;
use App\Container\Contracts\Users\UsersContract;
//use App\Container\Contracts\SubUsers\SubUsersContract;
use Carbon\Carbon;


class SessionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:reminder';

    use Notifiable;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification for user before the session starts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Schedule $schedule, UsersContract $user/*, SubUsersContract $sub_user*/)
    {
        parent::__construct();
        $this->schedule = $schedule;
        $this->user = $user;
     //   $this->sub_user = $sub_user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now =Carbon::now()->subMinutes(30)->format('H:i:s');
        $sessions = $this->schedule->get()->toArray();
        foreach($sessions as $session){
          if($session['time_from'] == $now){
                $user = $this->user->get($session['user_id']);
                $teacher = $this->user->get($session['teacher_id']);
                $message = 'Session will start within 30 minutes from now';
                Notification::send($user, new SessionReminderNotification($message));
                Notification::send($teacher, new SessionReminderNotification($message));
              /*  if($session['sub_user_id']){
                    $sub_user = $this->sub_user->get($session['sub_user_id']);
                    Notification::send($sub_user, new SessionReminderNotification($message));
                }*/
            }
        }
    }
}
