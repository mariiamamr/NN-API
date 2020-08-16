<?php
namespace Repos\Comments;

use Contracts\Comments\CommentsContract;
use App\Comment;


class CommentsRepo implements CommentsContract
{
  private $number_of_pages = 1;
  public function __construct(Comment $comment)
  {
    $this->comment = $comment;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->comment->findOrFail($id);
  }

  public function getAll()
  {
    return $this->comment->all();
  }

  public function set($type, $data)
  {
    $this->comment->message = $data->comment;
    return $type->comments()->save($this->comment);
  }

  public function update($id, $data)
  {
    return \tap($comment = $this->get($id), function ($comment) use ($data) {
      return $comment->update([
        "message" => $data->message
      ]);
    });
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->comment->where(function ($query) {
      return $query->where('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }
}
