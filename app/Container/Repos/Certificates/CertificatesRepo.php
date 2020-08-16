<?php
namespace Repos\Certificates;

use Contracts\Certificates\CertificatesContract;
use App\Certificate;


class CertificatesRepo implements CertificatesContract
{
  private $number_of_pages = 1;
  public function __construct(Certificate $certificates)
  {
    $this->certificates = $certificates;
    $this->number_of_pages = config('static.pagination.rowsPerPage');
  }

  public function get($id)
  {
    return $this->certificates->findOrFail($id);
  }

  public function getAll()
  {
    return $this->certificates->all();
  }

  public function set($data)
  {
    return $this->certificates->create([
      "label" => $data->label,
      "is_required" => $data->is_required,
      "slug" => make_slug($data->label)
    ]);
  }

  public function update($id, $data)
  {
    return $this->get($id)->update([
      "label" => $data->label,
      "is_required" => $data->is_required,
      "slug" => make_slug($data->label)
    ]);
  }

  public function delete($id)
  {
    return $this->get($id)->delete();
  }

  public function paginate($search)
  {
    return $this->certificates->where(function ($query) use ($search) {
      return $query->where('slug', 'like', '%' . $search . '%')
        ->orWhere('label', 'like', '%' . $search . '%');
    })->paginate($this->number_of_pages);
  }

  public function getList($array)
  {
    return $this->certificates->get($array);
  }
}
