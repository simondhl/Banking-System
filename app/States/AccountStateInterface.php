<?php

namespace App\States;

use App\Models\Account;

interface AccountStateInterface
{
    public function close(Account $account): array;
    public function freeze(Account $account): array;
    public function suspend(Account $account): array;
    public function activate(Account $account): array;
}
