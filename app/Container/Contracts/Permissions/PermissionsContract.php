<?php

namespace Contracts\Permissions;

use App\Permission;


interface PermissionsContract
{

  public function __construct(Permission $permission);
  public function get($id);
  public function getAll();
  public function getAllGrouped();
  public function getAllArranged();
  public function set($data);
  public function delete($id);
  public function paginate($data);
}

