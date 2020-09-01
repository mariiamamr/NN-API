<?php
namespace App\Container\Contracts\EduSystems;

use App\Container\Contracts\EduSystems\EduSystemsContract;
use App\Models\EduSystem;


class EduSystemsEloquent implements EduSystemsContract
{
  private $number_of_pages = 1;
  public function __construct(EduSystem $edu_system)
  {
    $this->edu_system = $edu_system;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->edu_system->findOrFail($id);
  }

  public function getAll()
  {
    return $this->edu_system->all();
  }

  public function set($data)
  {
    return $this->edu_system->create([
      "title" => $data->title,
      "slug" => make_slug($data->title)
    ]);
  }

  public function update($id, $data)
  {
    return $this->get($id)->update([
      "title" => $data->title,
      "slug" => make_slug($data->title)
    ]);
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->edu_system->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->edu_system->get($array);
  }
}
