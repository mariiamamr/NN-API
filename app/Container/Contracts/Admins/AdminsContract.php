<?php

/**
 * Created by PhpStorm.
 * User: Backend Dev
 * Date: 9/12/2018
 * Time: 11:30 AM
 */

namespace Contracts\Admins;


use App\Admin;

interface AdminsContract
{

    public function __construct(Admin $admin);

    public function get($id);

    public function getAll();

    public function set($data);

    public function update($id, $data);

    public function delete($id);

    public function paginate($search);

    public function changePassword($id, $data);

}