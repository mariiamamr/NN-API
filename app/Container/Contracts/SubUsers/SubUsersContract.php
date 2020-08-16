<?php
namespace Contracts\SubUsers;

use App\SubUser;
use Contracts\Files\FilesContract;


interface SubUsersContract
{
  public function __construct(SubUser $user,FilesContract $file);

  public function get($id);

  public function getAll();

  public function set($user_id, $data);

  public function update($id, $data);

  public function delete($id);

  public function paginate($search);
}
