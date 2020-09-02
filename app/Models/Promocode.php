<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    //
    public $fillable = [
        'code',
        'active',
        'percent',
        'valid_to',
        'valid_from',
        'usage_limit',
        'new_students',
    ];
}
