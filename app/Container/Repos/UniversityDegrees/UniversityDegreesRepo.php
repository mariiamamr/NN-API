<?php
namespace Repos\UniversityDegrees;

use Contracts\UniversityDegrees\UniversityDegreesContract;
use App\UniversityDegree;


class UniversityDegreesRepo implements UniversityDegreesContract
{
  private $number_of_pages = 1;
  public function __construct(UniversityDegree $university_degree)
  {
    $this->university_degree = $university_degree;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->university_degree->findOrFail($id);
  }

  public function getAll()
  {
    return $this->university_degree->all();
  }

  public function set($data)
  {
    return $this->university_degree->create([
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
    return $this->university_degree->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->university_degree->get($array);
  }
}
