<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\User;
use App\Models\Subject;
use App\Models\Lecture;

class SubjectUser extends Pivot
{
    //

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function lectures()
    {
        return $this->hasManyThrough(Lecture::class, User::class);
    }
}
