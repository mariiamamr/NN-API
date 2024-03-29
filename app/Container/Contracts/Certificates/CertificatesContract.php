<?php
namespace App\Container\Contracts\Certificates;

use App\Models\Certificate;



interface CertificatesContract
{
  public function __construct(Certificate $certificates);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
}
