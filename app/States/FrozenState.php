<?php

namespace App\States;

use App\Models\Account;

class FrozenState implements AccountStateInterface
{
    public function close(Account $account): array
    {
        return ['status' => false, 'message' => 'Cannot close a frozen account'];
    }

    public function activate(Account $account): array
    {
        $account->account_status_id = 1; // Active
        $account->save();
        return ['status' => true, 'message' => 'Account activated successfully'];
    }

    public function suspend(Account $account): array
    {
        $account->account_status_id = 3; // Suspended
        $account->save();
        return ['status' => true, 'message' => 'Account suspended successfully'];
    }

    public function freeze(Account $account): array
    {
        return ['status' => false, 'message' => 'Account is already frozen'];
    }
}
