<?php

namespace App\Services\TransactionApprovalChain;

class AutoApprovalHandler extends ApprovalHandler
{
    public function handle(float $amount, $user)
    {
        if ($amount <= 10000000) { 
            return [
                'approved' => true,
                'message' => 'Auto approved'
            ];
        }

        return parent::handle($amount, $user);
    }
}
