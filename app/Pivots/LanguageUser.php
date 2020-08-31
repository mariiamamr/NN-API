<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Lang;
use App\User;
use App\Models\Lecture;

class LanguageUser extends Pivot
{
    //

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function language()
    {
        return $this->belongsTo(Lang::class, 'language_id');
    }

    public function lectures()
    {
        return $this->hasManyThrough(Lecture::class, User::class);
    }
}
