<?php

namespace Contracts\Blogs;


use App\Blog;
use Contracts\Files\FilesContract;
use Contracts\Comments\CommentsContract;

interface BlogsContract
{

  public function __construct(Blog $blog, FilesContract $file_contract, CommentsContract $comment);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function latest();

  public function add_comment($id, $data);
}