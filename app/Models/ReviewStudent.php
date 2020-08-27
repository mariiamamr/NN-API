<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Lecture;
use App\User;

class ReviewStudent extends Model
{
    public $fillable=[
        'rate',
        'content',
        'user_id',
        'teacher_id',
        'lecture_id'
        ];
    
        public function teacher(){
            return $this->hasOne(User::class,'id','teacher_id');
        }
    
        public function lecture(){
            return $this->hasOne(Lecture::class,'id','lecture_id');
        }
    }


