<?php
namespace Contracts\Pages;

use App\Page;
use Contracts\Files\FilesContract;


interface PagesContract
{
  public function __construct(Page $page, FilesContract $file);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);

  public function getLinks($active=null);

  public function getBySlug($slug);
}