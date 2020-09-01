<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduSystem extends Model
{
    protected $fillable = [
        'title',
        'slug',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
