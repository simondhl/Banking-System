<?php

namespace App\Services;

use App\Models\Device_token;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

  public function login(array $request)
  {

    $user = User::where('user_number', ($request['user_number']))->first();

    // check if user_number and password(Hashed) match
    if ($user && Hash::check($request['password'], $user->password)) {

      // create access token
      $user['token'] = $user->createToken('AccessToken')->plainTextToken;

      $responseData = [
        'message' => 'Welcome',
        'token' => $user['token'],
        'role' => $user->role_id,
      ];

      return $responseData;
    }
    return false;
  }

  public function logout()
  {
    Auth::user()->currentAccessToken()->delete();
  }

  public function save_device_token(string $token)
  {
    $user = Auth::user();
    
    return Device_token::updateOrCreate(
        ['token' => $token],
        ['user_id' => $user->id,]
    );
  }


}
