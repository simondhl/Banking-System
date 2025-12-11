<?php
namespace App\Services\InterestStrategies;

use App\Models\Account;

class InvestmentInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account): float
    {
        $rate = 0.10; // 10%
        return $account->balance * $rate;
    }
}