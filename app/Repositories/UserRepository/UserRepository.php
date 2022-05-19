<?php

namespace App\Repositories\UserRepository;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

  public function create($data)
  {
    return User::create($data);
  }

  public function findById($user_id)
  {
    return User::where('id', $user_id)->first();
  }

  public function changePassword($user_id, $new_password)
  {
    return User::where('id', $user_id)->update(
      ['password' => bcrypt($new_password)]
    );
  }
}