<?php
namespace Contracts\Comments;

use App\Comment;


interface CommentsContract
{
  public function __construct(Comment $comment);

  public function get($id);

  public function getAll();

  public function set($type, $data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

}
