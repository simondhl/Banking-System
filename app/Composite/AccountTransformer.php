<?php

namespace App\Composite;

use App\Models\Account;

class AccountTransformer
{
    public static function transformAccount(Account $account)
    {
        return [
            'id' => $account->id,
            'account_number' => $account->account_number,
            'balance' => $account->balance,
            'type' => $account->account_type->type_name ?? null,
            'hierarchy' => $account->account_hierarchy->hierarchy_name ?? null,
            'status' => $account->account_status->status ?? null,
        ];
    }

    public static function transformComposite(AccountComposite $composite, $excludeRootId = null)
    {
        $children = $composite->getChildren();
        $result = [];

        foreach ($children as $child) {
            if ($child instanceof Account) {
                if ($excludeRootId && $child->id === $excludeRootId) {
                    continue;
                }
                $result[] = self::transformAccount($child);
            } else {
                $result = array_merge($result, self::transformComposite($child, $excludeRootId));
            }
        }

        return $result;
    }
}
