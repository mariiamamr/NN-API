<?php
namespace Contracts\Services\TokBox;


interface TokBoxContract
{
  public function __construct();

  public function create_session();

  public function generate_token($session_id, $options);

}