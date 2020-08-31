<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateModel extends Model
{
    protected $table='certificates';
    public $timestamps=false;
    protected $fillable=[
        'slug',
        'label',
        'is_required',
        
    ];
}
