<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountFormRequest;
use App\Http\Requests\UserFormRequest;
use App\Services\AccountService;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function create_employee(UserFormRequest $request)
    {
        $result = $this->userService->create_employee($request->validated());
        if (!$result) {
            return response()->json(['message' => 'Failed to add employee'], 400);
        }
        return response()->json(['message' => 'New employee added successfully'], 200);
    }
}
