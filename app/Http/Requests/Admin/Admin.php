<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\Backend\Base;
use Illuminate\Support\Facades\Route;

class Admin extends Base
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private $module = 'admin';
    public function rules()
    {
        if(Route::is('admin.auth.login')){
            return [
                'email'    => 'required|string|email',
                'password' => 'required|string|min:6'
            ];
        } elseif(Route::is('admin.auth.register')){
            return [
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|string|email|unique:admins',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6'
            ];
        } elseif(Route::is('admin.admin.store')){
            return [
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|string|email|unique:admins',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6'
            ];
        } elseif(Route::is('admin.admin.update')){
            $id = $this->route()->parameter($this->module);
            return [
                'name'  => 'required|string|max:255',
                'email' => 'required|string|email|unique:admins,email,' . $id,
            ];
        } elseif(Route::is('admin.profile.update')){
            $id         = $this->route()->parameter('id');
            $pass_valid = [];

            if( ! empty(request()->get('password'))){
                $pass_valid = [
                    'password'              => 'string|min:6|confirmed',
                    'password_confirmation' => 'required_with:password|string|min:6'
                ];
            }

            return array_merge([
                'name'  => 'string|max:255',
                'email' => 'string|email|unique:admins,email,' . $id,

            ], $pass_valid);
        } else{
            return [
                //
            ];
        }
    }
}
