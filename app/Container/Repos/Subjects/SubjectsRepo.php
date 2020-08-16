<?php

namespace Repos\Subjects;

use Contracts\Subjects\SubjectsContract;
use App\Subject;


class SubjectsRepo implements SubjectsContract
{
  private $number_of_pages = 1;
  public function __construct(Subject $subject)
  {
    $this->subject = $subject;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->subject->findOrFail($id);
  }

  public function getAll()
  {
    return $this->subject->all()->sortBy('title')->where('is_active', true);
  }

  public function set($data)
  {
    return $this->subject->create([
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
    return $this->subject->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->subject->get($array)->sortBy('title');
  }
}
