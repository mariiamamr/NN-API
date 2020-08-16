<?php
namespace Contracts\Newsletters;

use App\Newsletter;


interface NewslettersContract
{
  public function __construct(Newsletter $Grad);

  public function get($id);

  public function getAll();

  public function set($data);

  public function paginate();

}