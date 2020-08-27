<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\SubUser;

class Lecture extends Model
{
    //
    public $fillable = [
        "checkout_user_id",
        "started",
        "payed_user_id",
        "completed_at",
        "teacher_id",
        "date",
        "time_from",
        "time_to",
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class, //table A
            \App\Pivots\SubjectUser::class, // table B
            "user_id", //where query in table B
            "id", // join with table A
            "teacher_id", // id in current model for where query
            "subject_id" // 
        );
    }

    public function grades()
    {
        return $this->hasManyThrough(
            Grade::class, //table A
            \App\Pivots\GradeUser::class, // table B
            "user_id", //where query in table B
            "id", // join with table A
            "teacher_id", // id in current model for where query
            "grade_id" // 
        );
    }

    public function languages()
    {
        return $this->hasManyThrough(
            Language::class, //table A
            \App\Pivots\LanguageUser::class, // table B
            "user_id", //where query in table B
            "id", // join with table A
            "teacher_id", // id in current model for where query
            "language_id" // 
        );
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'schedules')->withPivot('price', 'date', 'time_from', 'time_to', 'teacher_id', 'type');
    }

    public function sub_attendees()
    {
        return $this->belongsToMany(SubUser::class, 'schedules')->withPivot('price', 'date', 'time_from', 'time_to', 'teacher_id', 'user_id', 'type');
    }
}
