<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $fillable = [
        'slug',
        'label',
        'is_required'
    ];

    public static $rules = [
        'label' => 'required',
        'slug' =>'unique:certificates'
    ];

    public function files()
    {
        return $this->belongsTo('App\File');
    }
}
