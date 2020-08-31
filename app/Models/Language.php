<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Pivots\LanguageUser;

class Language extends Model
{
    //
    protected $fillable = [
        "slug",
        "title"
    ];

    public $timestamps = false;


    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(LanguageUser::class);
    }
}
