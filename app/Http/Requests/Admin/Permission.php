<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class Permission extends Base
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.permission.store')) {
            return [
                'name' => 'required|string|min:4',
                'label' => 'required|string',
                'title' => 'required|string|min:4'
            ];
        } elseif (Route::is('admin.permission.update')) {
            return [
                'name' => 'required|string|min:4',
                'label' => 'required|string|min:4',
                'title' => 'required|string|min:4'
            ];
        } else {
            return [
                //
            ];
        }
    }
}
