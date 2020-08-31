<?php
namespace App\Container\Contracts\Languages;

use App\Models\Language;


interface LanguagesContract
{
  public function __construct(Language $lang);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);
  
  public function getList5($array);
}