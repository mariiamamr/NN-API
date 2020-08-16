<?php
namespace Repos\Roles;

use Contracts\Roles\RolesContract;
use App\Role;
use App\Permission;


class RolesRepo implements RolesContract
{
    private $number_of_pages = 1;

    public function __construct(Role $role)
    {
        $this->role = $role;
        $this->number_of_pages = config('static.pagination.rowsPerPage');
    }

    public function get($id)
    {
        return $this->role->findOrFail($id);
    }

    public function getAll()
    {
        return $this->role->all();
    }

    public function getPaginated()
    {
        return $this->role->paginate($this->pagination);
    }

    public function permissions($role)
    {
        return $role->belongsToMany(Permission::class);
    }

    public function permissionsIds($id)
    {
        $role = $this->get($id);

        return $this->permissions($role)->allRelatedIds()->toArray();
    }

    public function set($data)
    {
        $this->role->label = $data->label;
        $this->role->name = make_slug($data->label);

        $result = $this->role->save();

        if (!empty($data->permissions))
            $this->role->permissions()->sync($data->permissions);

        return $result;
    }

    public function update($id, $data)
    {
        $role = $this->get($id);

        $role->label = $data->label;
        $role->name = make_slug($data->label);

        $result = $role->save();

        if (!empty($data->permissions)){
            $role->permissions()->sync($data->permissions);
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->get($id)->delete();
    }

    public function paginate($search)
    {
        return $this->role->where(function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('label', 'like', '%' . $search . '%');
        })
            ->paginate($this->number_of_pages);
    }
}
