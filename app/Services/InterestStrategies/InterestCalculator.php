<?php
namespace App\Services\InterestStrategies;

use App\Models\Account;

class InterestCalculator
{
    public function getStrategy(Account $account): InterestStrategy
    {
        $type = strtolower($account->account_type->type_name);

        return match ($type) {
            'saving account' => new SavingInterestStrategy(),
            'checking account' => new CheckingInterestStrategy(),
            'loan account' => new LoanInterestStrategy(),
            'investment account' => new InvestmentInterestStrategy(),
            default => throw new \Exception("Unknown account type"),
        };
    }

    public function calculate(Account $account): float
    {
        $strategy = $this->getStrategy($account);
        return $strategy->calculate($account);
    }
}