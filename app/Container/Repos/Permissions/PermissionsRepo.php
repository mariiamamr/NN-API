<?php

namespace Repos\Permissions;

use Contracts\Permissions\PermissionsContract;
use App\Permission;
use Illuminate\Support\Facades\DB;


class PermissionsRepo implements PermissionsContract
{
  private $number_of_pages = 1;
  public function __construct(Permission $permission)
  {
    $this->permission = $permission;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->permission->findOrFail($id);
  }

  public function getAll()
  {
    return $this->permission->all();
  }

  public function getAllGrouped()
  {
      // sum id as order to order by based on id
    return $this->permission->select(DB::raw('sum(id) as ord, GROUP_CONCAT(id) as ids, GROUP_CONCAT(name) as names, GROUP_CONCAT(title) as titles, label'))
      ->groupBy('label')->orderBy('ord')->get();
  }

  public function getAllArranged()
  {
    $permissions = [];
    $groupedPermission = $this->getAllGrouped();
    foreach ($groupedPermission as $key => $permission) {
      $ids = explode(',', $permission->ids);
      $names = explode(',', $permission->names);
      $titles = explode(',', $permission->titles);

      $permissions[$key]['label'] = $permission->label;
      foreach ($ids as $permissionKey => $permissionId) {
        $permissions[$key]['permissions'][$permissionKey]['id'] = $permissionId;
        $permissions[$key]['permissions'][$permissionKey]['name'] = $names[$permissionKey];
        $permissions[$key]['permissions'][$permissionKey]['title'] = $titles[$permissionKey];
      }
    }

    return $permissions;
  }
  public function set($data)
  {
    return $this->permission->create([
      'name' => strtolower($data['name']),
      'label' => $data['label'],
      'title' => $data['title']
    ]);
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->permission->where(function ($query) use ($search) {
      return $query->where('name', 'like', '%' . $search . '%')
        ->orWhere('label', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })
      ->paginate($this->number_of_pages);
  }
}

