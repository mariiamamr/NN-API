<?php

namespace Repos\SubUsers;

use Contracts\SubUsers\SubUsersContract;
use App\SubUser;
use Contracts\Files\FilesContract;


class SubUsersRepo implements SubUsersContract
{
  public function __construct(SubUser $user, FilesContract $file)
  {
    $this->user = $user;
    $this->file = $file;
  }

  public function get($id)
  {
    return $this->user->findOrFail($id);
  }

  public function getAll()
  {
  }

  public function set($user_id, $data)
  {
    return $this->user->create([
      "first_name" => $data->first_name,
      "last_name" => $data->last_name,
      "username" => $data->name,
      "password" => \Hash::make($data->password),
      "user_id" => $user_id
    ]);
  }

  public function update($id, $data)
  {
  }

  public function delete($id)
  {
    $sub_user = $this->get($id);

    if ($sub_user->registeredSessions()->where('payed', false)->count() == 0) {
      return $sub_user->delete();
    }
    return null;
  }

  public function paginate($search)
  {

  }

  public function login($request)
  {

    $user = $this->user->where('username', $request->email)->first();
    if ($user && \Hash::check($request->password, $user->password)) {
      return $user;
    }
    return null;
  }

  public function uploadImageProfile($id, $data)
  {
    $user = $this->get($id);
    $user->image_url = $this->file->uploadSingle($data->image);
    $user->save();
    return $user;
  }
}
