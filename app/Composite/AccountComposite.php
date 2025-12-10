<?php

namespace App\Composite;

class AccountComposite implements AccountComponent
{
    protected array $accounts = [];

    public function add(AccountComponent $account)
    {
        $this->accounts[] = $account;
    }

    public function getBalance()
    {
        return array_sum(
            array_map(fn($acc) => $acc->getBalance(), $this->accounts)
        );
    }

    public function getAccountNumber()
    {
        return implode(
            ',',
            array_map(fn($acc) => $acc->getAccountNumber(), $this->accounts)
        );
    }

    public function getChildren()
    {
        return $this->accounts;
    }
}
