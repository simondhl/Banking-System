<?php

namespace App\Services\TransactionApprovalChain;

abstract class ApprovalHandler
{
    protected ?ApprovalHandler $nextHandler = null;

    public function setNext(ApprovalHandler $handler): ApprovalHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    public function handle(float $amount, $user)
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($amount, $user);
        }

        return [
            'approved' => true,
            'message' => 'Transaction approved automatically'
        ];
    }
}
