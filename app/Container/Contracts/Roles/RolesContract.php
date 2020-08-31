<?php
namespace App\Container\Contracts\Roles;

use App\Models\Role;


interface RolesContract
{

    public function __construct(Role $role);
    public function get($id);
    public function getAll();
    public function getPaginated();
    public function permissions($role);
    public function permissionsIds($id);
    public function set($data);
    public function update($id, $data);
    public function delete($id);
    public function paginate($data);
}