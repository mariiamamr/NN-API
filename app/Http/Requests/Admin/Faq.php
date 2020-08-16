<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class faq extends Base
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.faq.store')) {
            return [
                'title' => 'required|string|min:3|unique:faqs,title',
                'description' => 'required|string|min:3',
            ];
        } elseif (Route::is('admin.faq.update')) {
            $id = $this->route()->parameter($this->module)['faq'];
            return [
                'title' => 'required|string|min:3|unique:faqs,title,' . $id,
                'description' => 'required|string|min:3',
            ];
        } else {
            return [
                //
            ];
        }
    }
}
