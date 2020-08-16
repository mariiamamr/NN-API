<?php

namespace App\Http\Requests\Backend\Admin;

// use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class Blog extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.blog.store')) {
            return [
                "slug" => "string|max:255|unique:blogs,slug",
                "title" => "required|string|max:255",
                "description" => "required|string",
            ];
        } elseif (Route::is('admin.blog.update')) {
            return [
                "slug" => "string|max:255|unique:blogs,slug",
                "title" => "required|string|max:255",
                "description" => "required|string",
            ];
        } else {
            return [
                //
            ];
        }
    }
}
