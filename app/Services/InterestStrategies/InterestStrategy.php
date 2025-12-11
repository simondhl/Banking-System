<?php
namespace App\Services\InterestStrategies;

use App\Models\Account;

interface InterestStrategy
{
    // calculate interest
    public function calculate(Account $account): float;
}
