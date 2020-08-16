<?php
namespace Contracts\UniversityDegrees;

use App\UniversityDegree;



interface UniversityDegreesContract
{
  public function __construct(UniversityDegree $university_degree);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
}
