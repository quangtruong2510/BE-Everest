<?php

namespace App\Repositories\RefreshTokenRepository;

interface RefreshTokenRepositoryInterface {

  public function create($data);

  public function findByUserIdAndRefreshToken($user_id, $refresh_token);

  public function findByRefreshToken($refresh_token);

  public function delete($user_id, $refresh_token);

  public function deleteByUserId($user_id);
}