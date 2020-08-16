<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class UniversityDegree extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.uni_degree.store')) {
            return [
                'title' => 'required|string|min:2|unique:university_degrees,title'
            ];
        } elseif (Route::is('admin.uni_degree.update')) {
            $id = $this->route()->parameter($this->module)['uni_degree'];

            return [
                'title' => 'required|string|min:2|unique:university_degrees,title,' . $id
            ];
        } else {
            return [
                //
            ];
        }
    }
}
