<?php
namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


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

public function profile()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
       /// return 1;
    }
    public function uni_degress()
    {
        return $this->belongsTo(UniversityDegree::class);
    }
}


