<?php
namespace App\Services\InterestStrategies;

use App\Models\Account;

class SavingInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account): float
    {
        $rate = 0.015; // 1.5%
        return $account->balance * $rate;
    }
}