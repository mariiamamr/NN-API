<?php
namespace App\Container\Contracts\Grades;
use App\Container\Contracts\Grades\GradesContract as GradesContract;
use App\Models\Grade;
class GradesEloquent implements GradesContract {
    private $number_of_pages = 1;
    protected $grade;
  public function __construct(Grade $grade)
  {
    $this->grade = $grade;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->grade->findOrFail($id);
  }

  public function getAll()
  {
    return $this->grade->all();
  }

  public function set($data)
  {
    return $this->grade->create([
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
    return $this->grade->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->grade->get($array);
  }
} 
