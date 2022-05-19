<?php

namespace App\Repositories\RefreshTokenRepository;

use App\Models\RefreshToken;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface {

  public function create($data) {
    return RefreshToken::insert($data);
  }

  public function findByUserIdAndRefreshToken($user_id, $refresh_token) {
    return RefreshToken::where([
      'user_id' => $user_id,
      'refresh_token' => $refresh_token
    ]);
  }

  public function findByRefreshToken($refresh_token) {
    return RefreshToken::where([
      'refresh_token' => $refresh_token
    ]);
  }

  public function delete($user_id, $refresh_token) {
    return RefreshToken::where([
      'user_id' => $user_id,
      'refresh_token' => $refresh_token
    ])->delete();
  }

  public function deleteByUserId($user_id) {
    return RefreshToken::where([
      'user_id' => $user_id,
    ])->delete();
  }

}