<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Schedule extends Model
{

    public $fillable = [
        "user_id",
        "lecture_id",
        "teacher_id",
        "date",
        "time_from",
        "time_to",
        "price",
        "payed",
        "type",
        "status", 
        "group_id"
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function teachers()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }

    public function attendees()
    {
        return $this->hasMany(User::class,'id','user_id');
    }

    public function sub_attendees()
    {
        return $this->hasMany(SubUser::class,'id','sub_user_id');
    }

    public function teacherReviews()
    {
        return $this->hasMany(ReviewStudent::class, 'lecture_id', 'lecture_id');
    }
}
