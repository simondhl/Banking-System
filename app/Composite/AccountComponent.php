<?php

namespace App\Composite;

interface AccountComponent
{
    public function getBalance();
    public function getAccountNumber();
    public function getChildren();
}
