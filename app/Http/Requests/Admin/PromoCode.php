<?php

namespace App\Http\Requests\Backend\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class PromoCode extends Base
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Route::is('admin.promo.store')) {
            return [
                'code' => 'required|string|min:4|unique:promocodes,code',
                'percent' => 'required|numeric|min:0|max:100',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date'
            ];
        } elseif (Route::is('admin.promo.update')) {
            $id = $this->route()->parameter($this->module)['promo'];
            return [
                'code' => 'required|string|min:4|unique:promocodes,code,' . $id,
                'percent' => 'required|numeric|min:0|max:100',
                'valid_from' => 'required|date',
                'valid_to' => 'required|date'
            ];
        } else {
            return [
            //
            ];
        }
    }
}
