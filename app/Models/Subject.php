<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Pivots\SubjectUser;

class Subject extends Model
{
    //
    protected $fillable = [
        'title',
        'slug',
        'is_active'
    ];


    public function teachers()
    {
        return $this->belongsToMany(User::class)
            ->using(SubjectUser::class);
    }

    public function lectures()
    {
        // return $this->hasManyThrough(
        //     Subject::class, //table A
        //     \App\Pivots\SubjectUser::class, // table B
        //     "user_id", //where query in table B
        //     "id", // join with table A
        //     "teacher_id", // id in current model for where query
        //     "subject_id" // 
        // );
        return $this->hasManyThrough(
            Lecture::class,
            \App\Pivots\SubjectUser::class,
            "subject_id",
            "teacher_id",
            "",
            "user_id"
        );
    }
}
