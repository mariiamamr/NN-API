<?php
namespace Repos\Services\TokBox;

use Contracts\Services\TokBox\TokBoxContract;
use \OpenTok\OpenTok;



class TokBoxRepo implements TokBoxContract
{
  private const OPENTOK_API_KEY = '46247572';
  private const OPENTOK_API_SECRET = '378c6fcfba864123fc1abc627234f1a05b414880';

  public function __construct()
  {
    $this->apiObj = new OpenTok(self::OPENTOK_API_KEY, self::OPENTOK_API_SECRET);
  }

  public function create_session()
  {
    $session = $this->apiObj->createSession(array('mediaMode' => \OpenTok\MediaMode::RELAYED));
    return $session->getSessionId();
  }

  public function generate_token($session_id, $options = [])
  {
    return $this->apiObj->generateToken($session_id, $options);
  }

}
