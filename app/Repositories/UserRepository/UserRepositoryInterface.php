<?php

namespace App\Repositories\UserRepository;

interface UserRepositoryInterface {
  public function create($data);

  public function findById($user_id);

  public function changePassword($user_id, $new_password);
}