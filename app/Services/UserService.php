<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function create_employee(array $request)
    {   
        $user = User::query()->create([
            'phone_number' => $request['phone_number'],
            'user_number' => $request['user_number'],
            'national_number' => $request['national_number'],
            'password' => Hash::make($request['password']),
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'location' => $request['location'],
            'role_id' => 2,
        ]);
        if (!$user) {
            return false;
        }
        return true;
    }

}
