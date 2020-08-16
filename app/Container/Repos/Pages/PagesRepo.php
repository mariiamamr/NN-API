<?php

namespace Repos\Pages;

use Contracts\Pages\PagesContract;
use App\Page;
use Contracts\Files\FilesContract;


class PagesRepo implements PagesContract
{
  private $number_of_pages = 1;
  public function __construct(Page $page, FilesContract $file)
  {
    $this->page = $page;
    $this->file = $file;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->page->findOrFail($id);
  }

  public function getAll()
  {
    return $this->page->all();
  }

  public function set($data)
  {
    if (isset($data->thumb)) {
      $file = $data->thumb;
      if ($file->isValid()) {
        $fileName = $this->file->uploadCertificate($file);
      }
    }

    return $this->page->create(array_merge([
      "title" => $data->title,
      "slug" => str_slug($data->title),
      "content" => $data->content,
      "active" => (bool) $data->active,
      "image_url" => $fileName
    ]));
  }

  public function update($id, $data)
  {
    if (isset($data->thumb)) {
      $file = $data->thumb;
      if ($file->isValid()) {
        $fileName = $this->file->uploadCertificate($file);
      }
    }

    return $this->get($id)->update(array_merge([
      "title" => $data->title,
      "slug" => str_slug($data->title),
      "content" => $data->content,
      "active" =>  (bool) $data->active,
      "image_url" => $fileName
    ]));
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->page->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->page->get($array);
  }

  public function getLinks($active = null)
  {
    $query = $this->page;
    if (!is_null($active)) {
      $query = $query->where('active', $active);
    }
    return $query->pluck('title', 'slug');
  }

  public function getBySlug($slug)
  {
    return $this->page->where('slug', $slug)->first();
  }
}
