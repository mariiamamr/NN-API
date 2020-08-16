<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{

    //
    protected $table='grades';
    protected $fillable = [
        'title',
        'slug',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(GradeUser::class);
    }
}
