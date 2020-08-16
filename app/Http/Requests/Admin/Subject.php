<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class Subject extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.subject.store')) {
            return [
                'title' => 'required|string|min:4|unique:subjects,title'
            ];
        } elseif (Route::is('admin.subject.update')) {
            $id = $this->route()->parameter($this->module)['subject'];

            return [
                'title' => 'required|string|min:4|unique:subjects,title,' . $id
            ];
        } else {
            return [
            //
            ];
        }
    }
}
