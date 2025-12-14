<?php

namespace App\Transformer;

use App\Models\Account;

class AccountTransformer
{
    public static function transform(Account $account)
    {
        $parentAccountNumber = null;
        if ($account->parent_account_id) {
            $parent = Account::where('id', $account->parent_account_id)->first();
            $parentAccountNumber = $parent->account_number ?? null;
        }

        return [
            'account_number' => $account->account_number,
            'balance' => $account->balance,
            'account_type' => $account->account_type->type_name ?? null,
            'account_hierarchy' => $account->account_hierarchy->hierarchy_name ?? null,
            'account_status' => $account->account_status->status ?? null,
            'parent_account_number' => $parentAccountNumber,
            'created_at' => $account->created_at,
        ];
    }
}
