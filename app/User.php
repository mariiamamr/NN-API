<?php
namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\PasswordResetNotification;
use App\Notifications\CustomVerifyEmailNotification;
use App\Pivots\SubjectUser;
use App\Pivots\GradeUser;
use App\Pivots\LanguageUser;
use App\Models\Subject;
use App\Models\File;
use App\Models\Grade;
use App\Models\Language;
use App\UserInfo;
use App\Models\Lecture;
use App\Models\Review;
use App\Models\RewiewStudent;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, Notifiable;
/**
* The attributes that are mass assignable.
*
* @var array
*/
protected $fillable = [
'email', 'password','full_name','type','gender','status','username','birth'
];
/**
* The attributes that should be hidden for arrays.
*
* @var array
*/
protected $hidden = [
'password', 'remember_token',
];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
         $this->notify(new PasswordResetNotification($token));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmailNotification() );
    }

   public function image()
    {
        return $this->belongsTo(File::class);
    }

    public function profile()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function specialist_in()
    {
        return $this->belongsToMany(Subject::class)
            ->using(SubjectUser::class);
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class)
            ->using(GradeUser::class);
    }

    public function uni_degress()
    {
        return $this->belongsTo(UniversityDegree::class);
    }

  /*  public function edu_systems()
    {
        return $this->belongsToMany(EduSystem::class, 'edu_user', 'user_id', 'edu_id');
    }
*/
    public function languages()
    {
        return $this->belongsToMany(Language::class)
            ->using(LanguageUser::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'teacher_id');
    }

    public function available_lectures($query)
    {
        return $query->with('lectures')->where('started', false);
    }

   /* public function subusers()
    {
        return $this->hasMany(SubUser::class, 'user_id');
    }
*/
    public function registeredSessions()
    {
        return $this->belongsToMany(Lecture::class, 'schedules', 'user_id');
    }

  /*  public function withdraws()
    {
        return $this->hasMany(Withdraw::class, 'user_id');
    }
*/
    public function hasProfile()
    {
        return !is_null($this->profile);
    }

 /*   public function credit_cards()
    {
        return $this->hasMany(PaymentInfo::class, 'user_id');
    }
*/
    public function latestReviews()
    {
        return $this->hasManyThrough(Review::class, UserInfo::class,'user_id','user_id')->take(3)->latest();
    }

    public function studentReviews()
    {
        return $this->hasManyThrough(ReviewStudent::class, UserInfo::class,'user_id','user_id')->take(3)->latest();
    }

  /*  public function users()
    {
        return $this->hasMany(Report::class);
    }

    public function teacher()
    {
        return $this->hasMany(Report::class, 'teacher_id');
    }
*/
}


