<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Base;
use Illuminate\Support\Facades\Route;

class Grade extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('api.grade.store')) {
            return [
                'title' => 'required|string|min:4|unique:grades,title'
            ];
        } elseif (Route::is('api.grade.update')) {
            $id = $this->route()->parameter($this->module)['grade'];

            return [
                'title' => 'required|string|min:4|unique:grades,title,' . $id
            ];
        } else {
            return [
            //
            ];
        }
    }
}
