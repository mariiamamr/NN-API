<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 9/12/2018
 * Time: 11:29 AM
 */

namespace Repos\Admins;


use App\Admin;
use Contracts\Admins\AdminsContract;
use Illuminate\Support\Facades\Auth;

class AdminsRepo implements AdminsContract
{
    private $number_of_pages = 1;
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
    }

    public function get($id)
    {
        return $this->admin->findOrFail($id);
    }

    public function getAll()
    {
        return $this->admin->all();
    }

    public function set($data, $confirm_employee = false)
    {
        return $this->admin->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
            'confirm_employee' => $confirm_employee,
            'role_id' => $data['role']
        ]);

    }

    public function update($id, $data)
    {
        return $this->get($id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role']
        ]);
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }

    public function paginate($search)
    {
        return $this->admin->where('id', '!=', [Auth::guard('admin')->id()])
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->paginate($this->number_of_pages);
    }

    public function changePassword($id, $data)
    {
        if (!isset($data['password'])) {
            return $this->update($id, $data);
        }

        return $this->get($id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password'])
        ]);
    }
}