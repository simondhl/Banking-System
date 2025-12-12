<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthFormRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthFormRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json(['message' => 
                'The entered number and password do not match, please try again'],401);
        }

        return response()->json($result, 200);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'you logged out successfully'
        ]);
    }
  
}
