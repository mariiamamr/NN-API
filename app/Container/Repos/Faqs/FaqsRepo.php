<?php
namespace Repos\Faqs;

use Contracts\Faqs\FaqsContract;
use App\Faq;

class FaqsRepo implements FaqsContract
{
  private $number_of_pages = 1;
  public function __construct(Faq $faq)
  {
    $this->faq = $faq;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->faq->findOrFail($id);
  }

  public function getAll()
  {
    return $this->faq->all();
  }

  public function set($data)
  {
    return $this->faq->create([
      "title" => $data->title,
      "slug" => make_slug($data->title),
      "description" => $data->description
    ]);
  }

  public function update($id, $data)
  {
    return $this->get($id)->update([
      "title" => $data->title,
      "slug" => make_slug($data->title),
      "description" => $data->description
    ]);
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->faq->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('title', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->faq->get($array);
  }
}
