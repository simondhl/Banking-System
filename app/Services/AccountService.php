<?php

namespace App\Services;

use App\Composite\AccountHierarchyBuilder;
use App\Composite\AccountTransformer;
use App\Models\Account;
use App\Models\Account_hierarchy;
use App\Models\Account_type;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\InterestStrategies\InterestCalculator;
use Illuminate\Support\Facades\Hash;


class AccountService
{
    public function create_account(array $request)
    {
        return DB::transaction(
            function () use ($request) {
                $parentAccountId = null;
                if (
                    isset($request['parent_account_number']) &&
                    $request['parent_account_number'] !== $request['user_number'] && $request['account_hierarchy'] !== 'individual account'
                ) {
                    $parentAccount = Account::where('account_number', $request['parent_account_number'])->first();
                    if (Account_hierarchy::where('id', $parentAccount['account_hierarchy_id'])->first()->hierarchy_name !== "group account") {
                        return [
                            'message' => 'The parent account should be group account',
                            'status' => false,
                        ];
                    }
                    $parentAccountId = $parentAccount->id;
                }
                $user = User::query()->create([
                    'phone_number' => $request['phone_number'],
                    'user_number' => $request['user_number'],
                    'national_number' => $request['national_number'],
                    'password' => Hash::make($request['password']),
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'location' => $request['location'],
                    'role_id' => 1,
                ]);
                if (!$user) {
                    return false;
                }
                $type = Account_type::where('type_name', $request['account_type'])->first();
                $hierarchy = Account_hierarchy::where('hierarchy_name', $request['account_hierarchy'])->first();

                $account = Account::query()->create([
                    'account_number' => $request['user_number'],
                    'balance' => $request['balance'],
                    'user_id' => $user->id,
                    'account_type_id' => $type->id,
                    'account_hierarchy_id' => $hierarchy->id,
                    'account_status_id' => 1,
                    'parent_account_id' => $parentAccountId,
                ]);
                if ($account) {

                    return [
                        'message' => 'Account created successfully',
                        'status' => true,
                    ];
                }
                return false;
            }
        );
    }

    public function close_account($id)
    {
        $account = Account::where('id', $id)->first();
        if ($account) {
            $account->close();
            $responseData = [
                'message' => 'Account cloased successfully'
            ];
            return $responseData;
        }
        return false;
    }
    public function search_account(array $request)
    {
        $account = Account::where('account_number', $request['account_number'])->first();

        if (!$account) {
            return false;
        }
        $tree = AccountHierarchyBuilder::buildTree($account);

        $calculator = new InterestCalculator();
        $interest = $calculator->calculate($account);

        return [
            'total_balance' => $tree->getBalance(),
            'account_numbers' => $tree->getAccountNumber(),
            'main_account' => AccountTransformer::transformAccount($account),
            'children' => AccountTransformer::transformComposite($tree, $account->id),
            'interest' => $interest,
            'new_balance_after_interest' => $account->balance + $interest,
        ];
    }
    public function update_account(array $request, $id)
    {
        $account = Account::where('id', $id)->first();
        if (!$account) {
            return [
                'status' => false,
                'message' => 'The account does not exist',
            ];
        }
        $updatedData = [];
        if (isset($request['parent_account_number'])) {

            if (Account_hierarchy::where('id', $account->account_hierarchy_id)->first()->hierarchy_name === 'individual account') {
                return [
                    'status' => false,
                    'message' => 'You can not update parent account for individual account',
                ];
            }

            if ($account->account_number === $request['parent_account_number']) {
                return [
                    'status' => false,
                    'message' => 'The parent account can not be the same account'
                ];
            }
            $parentAccount = Account::where('account_number', $request['parent_account_number'])->first();

            if (Account_hierarchy::where('id', $parentAccount['account_hierarchy_id'])->first()->hierarchy_name !== "group account") {
                return [
                    'message' => 'The parent account should be group account',
                    'status' => false,
                ];
            }
            $parentAccount = Account::where('account_number', $request['parent_account_number'])->first();
            $updatedData['parent_account_id'] = $parentAccount['id'];
        }
        if (empty($updatedData) && !isset($request['account_status'])) {
            return [
                'status' => false,
                'message' => 'Please enter at least one field to edit',
            ];
        }
        if (isset($request['account_status'])) {
            $status = strtolower($request['account_status']);

            $result = match ($status) {
                'closed'   => $account->close(),
                'frozen'   => $account->freeze(),
                'suspended' => $account->suspend(),
                'active'   => $account->activate(),
            };

            if (!$result['status']) {
                return $result;
            }
        }

        $account->update($updatedData);

        return [
            'status' => true,
            'message' => 'Account updated successfully',
        ];
    }
}
