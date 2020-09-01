<?php
namespace App\Container\Contracts\EduSystems;

use App\Models\EduSystem;



interface EduSystemsContract
{
  public function __construct(EduSystem $edu_system);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
}
