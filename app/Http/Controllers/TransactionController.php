<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionFormRequest;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function deposit_or_withdrawal(TransactionFormRequest $request)
    {
        $result = $this->transactionService->deposit_or_withdrawal($request->validated());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function transfer(TransactionFormRequest $request)
    {
        $result = $this->transactionService->transfer($request->validated());

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
