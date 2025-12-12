<?php

namespace App\States;

use App\Models\Account;

class ClosedState implements AccountStateInterface
{
    public function close(Account $account): array
    {
        return ['status' => false, 'message' => 'Account is already closed'];
    }

    public function activate(Account $account): array
    {
        return ['status' => false, 'message' => 'Cannot activate a closed account'];
    }

    public function suspend(Account $account): array
    {
        return ['status' => false, 'message' => 'Cannot suspend a closed account'];
    }

    public function freeze(Account $account): array
    {
        return ['status' => false, 'message' => 'Cannot freeze a closed account'];
    }
}
