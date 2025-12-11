<?php

namespace App\Services\TransactionApprovalChain;

class ManagerApprovalHandler extends ApprovalHandler
{

    public function handle(float $amount, $user)
    {
        if ($amount > 20000000) {

            if ($user->role->role_name !== 'manager') {
                return [
                    'approved' => false,
                    'message' => 'Transaction requires manager approval'
                ];
            }

            return [
                'approved' => true,
                'message' => 'Approved by manager'
            ];
        }

        return parent::handle($amount, $user);
    }
}
