<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountFormRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function create_account(AccountFormRequest $request)
    {
        $result = $this->accountService->create_account($request->validated());
        if (!$result) {
            return response()->json(['message' =>
            'Failed to create account'], 400);
        }

        if (!$result['status']) {
            return response()->json(['message' => $result['message']], 400);
        } elseif ($result['status']) {
            return response()->json(['message' => $result['message']], 200);
        }
    }
    public function close_account($id)
    {
        $result = $this->accountService->close_account($id);
        if (!$result) {
            return response()->json(['message' =>
            'The account does not exist'], 404);
        }
        return response()->json($result, 200);
    }
    public function search_account(AccountFormRequest $request)
    {
        $result = $this->accountService->search_account($request->validated());
        if (!$result) {
            return response()->json(['message' =>
            'The account does not exist'], status: 404);
        }
        return response()->json($result, 200);
    }
    public function update_account(AccountFormRequest $request, $id)
    {
        $result = $this->accountService->update_account($request->validated(), $id);
        if ($result['message'] === 'The account does not exist') {
            return response()->json(['message' => $result['message']], 404);
        }

        if ($result['status'] === false) {
            return response()->json(['message' => $result['message']], 400);
        }
        return response()->json([
            'message' => $result['message'],
        ], 200);
    }
}
