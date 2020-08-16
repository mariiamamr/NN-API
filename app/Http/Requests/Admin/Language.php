<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class Language extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.language.store')) {
            return [
                'title' => 'required|string|min:6|unique:languages,title'
            ];
        } elseif (Route::is('admin.language.update')) {
            $id = $this->route()->parameter($this->module)['language'];
            return [
                'title' => 'required|string|min:6|unique:languages,title,' . $id
            ];
        } else {
            return [
            //
            ];
        }
    }
}
