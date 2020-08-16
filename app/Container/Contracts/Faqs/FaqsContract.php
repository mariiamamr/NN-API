<?php

namespace Contracts\Faqs;

use App\Faq;

interface FaqsContract
{
  public function __construct(Faq $faq);

  public function get($id);

  public function getAll();

  public function set($data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);

  public function getList($array);

}
