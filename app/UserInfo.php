<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    //
    public $table = "user_info";
    public $fillable = [
        "rank",
        "phone",
        "weekly",
        "courses",
        "user_id",
        "avg_rate",
        "exp_desc",
        "exp_years",
        "month_rate",
        "price_info",
        "postal_code",
        'national_id',
        "rates_count",
        "nationality",
        "payment_info",
        "master_degree",
        "certifications",
        "suggested_subjects",
        'university_degree_id',
        'other_subjects'
    ];
    
    public $timestamps = false;
    
    protected $casts = [
        'weekly'            => 'array',
        'phones'            => 'array',
        'courses'           => 'array',
        'price_info'        => 'array',
        'national_id'       => 'array',
        'payment_info'      => 'array',
        'certifications'    => 'array',
        "suggested_subjects"=> 'array',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
