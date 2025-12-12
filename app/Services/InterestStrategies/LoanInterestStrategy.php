<?php
namespace App\Services\InterestStrategies;

use App\Models\Account;

class LoanInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account): float
    {
        $rate = -0.07; // 7%
        return $account->balance * $rate;
    }
}