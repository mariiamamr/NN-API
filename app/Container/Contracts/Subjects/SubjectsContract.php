<?php
namespace App\Container\Contracts\Subjects;

use App\Models\Subject;


interface SubjectsContract
{
  public function __construct(Subject $subject);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
}