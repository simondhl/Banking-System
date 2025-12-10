<?php

namespace App\Composite;

use App\Models\Account;

class AccountHierarchyBuilder
{
    public static function buildTree(Account $rootAccount)
    {
        $composite = new AccountComposite();

        $composite->add($rootAccount);

        foreach ($rootAccount->children as $child) {
            $composite->add(self::buildTree($child));
        }

        return $composite;
    }
}
