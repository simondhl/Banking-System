<?php

namespace App\States;

use App\Models\Account;

class SuspendedState implements AccountStateInterface
{
    public function close(Account $account): array
    {
        $account->account_status_id = 4; // Closed
        $account->save();
        return ['status' => true, 'message' => 'Account closed successfully'];
    }

    public function activate(Account $account): array
    {
        $account->account_status_id = 1; // Active
        $account->save();
        return ['status' => true, 'message' => 'Account activated successfully'];
    }

    public function suspend(Account $account): array
    {
        return ['status' => false, 'message' => 'Account is already suspended'];
    }

    public function freeze(Account $account): array
    {
        $account->account_status_id = 2; // Frozen
        $account->save();
        return ['status' => true, 'message' => 'Account frozen successfully'];
    }
}
