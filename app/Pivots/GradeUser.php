<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Grade;
use App\Providers\User;

class GradeUser extends Pivot
{
    //
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
