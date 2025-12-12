<?php

namespace App\States;

use App\Models\Account;

class ActiveState implements AccountStateInterface
{
    public function close(Account $account): array
    {
        $account->account_status_id = 4; // Closed
        $account->save();
        return ['status' => true, 'message' => 'Account closed successfully'];
    }

    public function activate(Account $account): array
    {
        return ['status' => false, 'message' => 'Account is already active'];
    }

    public function suspend(Account $account): array
    {
        $account->account_status_id = 3; // Suspended
        $account->save();
        return ['status' => true, 'message' => 'Account suspended successfully'];
    }

    public function freeze(Account $account): array
    {
        $account->account_status_id = 2; // Frozen
        $account->save();
        return ['status' => true, 'message' => 'Account frozen successfully'];
    }
}
