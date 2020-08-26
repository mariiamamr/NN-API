<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    public $fillable = [
        "name",
        "value",
        "type",
        "label"
    ];

    public $timestamps = false;
} 