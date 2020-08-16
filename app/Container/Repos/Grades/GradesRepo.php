<?php

namespace Repos\Grades;

use Contracts\Grades\GradesContract;
use App\Grade;


class GradesRepo implements GradesContract
{
  private $number_of_pages = 1;
  public function __construct(Grade $grad)
  {
    $this->grad = $grad;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->grad->findOrFail($id);
  }

  public function getAll()
  {
    return $this->grad->all();
  }

  public function set($data)
  {
    return $this->grad->create([
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
    return $this->grad->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->grad->get($array);
  }
}
