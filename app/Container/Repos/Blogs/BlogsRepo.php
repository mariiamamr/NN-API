<?php

namespace Repos\Blogs;


use App\Blog;
use Contracts\Blogs\BlogsContract;
use Illuminate\Support\Facades\Auth;
use Contracts\Files\FilesContract;
use Contracts\Comments\CommentsContract;

class BlogsRepo implements BlogsContract
{
  private $number_of_pages = 1;
  public function __construct(Blog $blog, FilesContract $file_contract, CommentsContract $comment)
  {
    $this->blog = $blog;
    $this->file = $file_contract;
    $this->comment = $comment;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->blog->findOrFail($id);
  }

  public function getAll()
  {
    return $this->blog->all();
  }

  public function set($data, $confirm_employee = false)
  {
    $images = [];
    if ($data->hasFile('thumb')) {
      $images = [
        "image_url" => $this->file->uploadSingle($data->thumb),
        // "image_id" => $this->file->set($data)
      ];
    }
    return $this->blog->create(array_merge([
      "title" => $data->title,
      "slug" => make_slug($data->title),
      "description" => $data->description
    ], $images));

  }

  public function update($id, $data)
  {
    $images = [];
    if ($data->hasFile('thumb')) {
      $images = [
        "image_url" => $this->file->uploadSingle($data->thumb)
        // "image_id" => $this->file->set($data)
      ];
    }

    return $this->get($id)->update(array_merge([
      "title" => $data->title,
      "slug" => make_slug($data->title),
      "description" => $data->description
    ], $images));
  }

  public function delete($id)
  {
    $blog = $this->get($id);
    // $this->file->delete($blog->image_id);
    return $blog->delete();
  }

  public function paginate($search)
  {
    return $this->blog->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })
      ->paginate($this->number_of_pages);
  }

  public function latest()
  {
    return $this->blog->latest()->take(3)->get();
  }

  public function add_comment($id, $data)
  {
    return $this->comment->set($this->get($id), $data);
  }
}