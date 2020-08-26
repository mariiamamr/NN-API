<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function students()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}