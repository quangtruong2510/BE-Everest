<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use App\Models\RefreshToken;
use DateTime;
use App\Repositories\RefreshTokenRepository\RefreshTokenRepositoryInterface;
use App\Repositories\UserRepository\UserRepositoryInterface;

class AuthController extends Controller
{
  protected $refresh_token_repo;
  protected $user_repo;

  public function __construct(
    RefreshTokenRepositoryInterface $refresh_token_repo,
    UserRepositoryInterface $user_repo
    ) {
    $this->refresh_token_repo = $refresh_token_repo;
    $this->user_repo = $user_repo;

    $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
  }

  public function login(Request $request){
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    if (!auth()->attempt($validator->validated())) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->createNewToken();
  }

  public function register(Request $request) {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|between:6,255|confirmed',
      'password_confirmation' => 'required ',
    ]);

    if($validator->fails()){
      return response()->json($validator->errors()->toJson(), 400);
    }

    $data = array_merge(
      $validator->validated(),
      ['password' => bcrypt($request->password)]
    );

    // $user = User::create(array_merge(
    //     $validator->validated(),
    //     ['password' => bcrypt($request->password)]
    // ));

    $user = $this->user_repo->create($data);

    return response()->json([
      'message' => 'User successfully registered',
      'user' => $user
    ], 201);
  }

  public function logout(Request $request) {
    //delete RF
    $user_id = auth()->user()->id;
    $refresh_token = $request->refresh_token;

    $result = $this->refresh_token_repo->delete($user_id, $refresh_token);
    
    // logout
    auth()->logout();

    return response()->json(['message' => 'User successfully signed out']);
  }

  public function refresh(Request $request) {
    $validator = Validator::make($request->all(), [
      'refresh_token' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 401);
    }

    return $this->createNewToken($request->refresh_token);
  }

  public function userProfile() {
    return response()->json(auth()->user());
  }

  protected function createNewToken($refresh_token = null) {
    if ($refresh_token) {
      // validation
      // check DB
      $found_refresh_token = $this->refresh_token_repo->findByRefreshToken($refresh_token);

      if ($data = $found_refresh_token->first()) {
        $user = $this->user_repo->findById($data->user_id);
        auth()->login($user);

        // delete old RF token
        $deleted_refresh_token = $this->refresh_token_repo->delete(auth()->user->id, $refresh_token);
        // compare with now() | $data->expired >= now()
        if ($data->expired_at < now()) {
          return response()->json(['message' => 'Unauthorized'], 401);
        }

        $generateTokenAndRefreshToken = $this->generateTokenAndRefreshToken();

      } else {
        return response()->json(['message' => 'Unauthorized'], 401);
      }
    } else { // login
      $generateTokenAndRefreshToken = $this->generateTokenAndRefreshToken();
    }

    $expired_token = now()->addDays(1);

    return response()->json([
      'access_token' => [
        'token'=> $generateTokenAndRefreshToken['token'],
        'type' => 'bearer',
        'expired_at' => $expired_token
      ],
      'refresh_token' => [
        'token'=> $generateTokenAndRefreshToken['refresh_token'],
        'expired_at' => $generateTokenAndRefreshToken['expired_r_token']
      ],
      'user' => auth()->user()
    ]);
  }

  public function generateTokenAndRefreshToken() {
    $expired_r_token = now()->addDays(7);

    $newToken = auth()->refresh();

    $newRefreshToken = uniqid() . '_'. bcrypt(auth()->user()->id);

    $data = [
      'user_id' => auth()->user()->id,
      'refresh_token' => $newRefreshToken,
      'expired_at' => $expired_r_token
    ];
    $result = $this->refresh_token_repo->create($data);

    return [
      'token' => $newToken, 
      'refresh_token' => $newRefreshToken,
      'expired_r_token' => $expired_r_token,
    ];
  }

  public function changePassWord(Request $request) {
    $validator = Validator::make($request->all(), [
      'old_password' => 'required|string|min:6',
      'new_password' => 'required|string|confirmed|min:6',
    ]);

    if($validator->fails()){
      return response()->json($validator->errors()->toJson(), 401);
    }

    $userId = auth()->user()->id;

    $user = $this->user_repo->changePassword($userId, $request->new_password);

    //delete RF
    $result = $this->refresh_token_repo->deleteByUserId($user_id);

    return response()->json([
      'message' => 'User successfully changed password',
      'user' => $user,
      'token' => $this->createNewToken()
    ], 201);
  }
}
