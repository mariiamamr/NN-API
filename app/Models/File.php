<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'title',
        'ext',
        'size',
        'mime',
        'original_title',
        'type'
    ];

}
