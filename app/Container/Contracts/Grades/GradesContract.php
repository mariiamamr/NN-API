<?php
namespace App\Container\Contracts\Grades;

use App\Models\Grade;


interface GradesContract
{
  public function __construct(Grade $Grad); //intialize model here

  public function get($id);

  public function getAll();

 public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
}