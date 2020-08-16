<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;

class Config extends Base
{

    public function rules()
    {
        return [
           "session_duration"=>"required|",
           "phone"=>"required|",
           "info_email"=>"required|email",
           "address"=>"required|string",
           "social_urls"=>"required|",
           "about_us"=>"required|string|max:365",
           "group_number" => "required",
           "percent" => "required"
        ];
    }
}
