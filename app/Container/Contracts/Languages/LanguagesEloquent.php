<?php

namespace App\Container\Contracts\Languages;

use App\Container\Contracts\Languages\LanguagesContract;
use App\Models\Language;


class LanguagesEloquent implements LanguagesContract
{
  private $number_of_pages = 1;
  public function __construct(Language $lang)
  {
    $this->lang = $lang;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->lang->findOrFail($id);
  }

  public function getAll()
  {
    return $this->lang->all();
  }

  public function set($data)
  {
    return $this->lang->create([
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
    return $this->lang->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->lang->get($array);
  }

  public function getList5($array)
  {

  }
}
