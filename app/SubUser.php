<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SubUser extends Authenticatable
{
    // protected $gurad = 'web';

    public $fillable = [
        "user_id",
        "first_name",
        "last_name",
        "password",
        "username"
    ];

    public function registeredSessions()
    {
        return $this->belongsToMany(Lecture::class, 'schedules', 'user_id');
    }
}


