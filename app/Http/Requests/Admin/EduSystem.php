<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class EduSystem extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.edusystem.store')) {
            return [
                'title' => 'required|string|min:3|unique:edu_systems,title'
            ];
        } elseif (Route::is('admin.edusystem.update')) {
            $id = $this->route()->parameter($this->module)['edusystem'];
            return [
                'title' => 'required|string|min:3|unique:edu_systems,title,' . $id
            ];
        } else {
            return [
                //
            ];
        }
    }
}
