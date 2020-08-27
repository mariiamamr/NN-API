<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Lecture;
use App\User;


class Review extends Model
{
    public $fillable=[
        'rate',
        'content',
        'user_id',
        'student_id',
        'lecture_id'
    ];

    public function student(){
        return $this->hasOne(User::class,'id','student_id');
    }

    public function lecture(){
        return $this->hasOne(Lecture::class,'id','lecture_id');
    }

}

