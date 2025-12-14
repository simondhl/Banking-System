<?php

namespace App\Observers;

use App\Jobs\SendFcmNotification;
use App\Models\Account;
use App\Models\Accounts_transaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        DB::afterCommit(function () use ($transaction) {
            Log::info("Added Transaction: {$transaction->id}");
            
            $accountsTransactions = Accounts_transaction::where(
                'transaction_id',
                $transaction->id
            )->get();
            
            foreach ($accountsTransactions as $accTrans) {
            
                $account = Account::where('id', $accTrans->account_id)->first();
                Cache::forget("transactions:account:{$account->id}");
            
                if (!$account || !$account->user) {
                    continue;
                }
            
                $user = $account->user;
            
                $message = $this->buildMessage($transaction, $accTrans);
            
                foreach ($user->device_token as $device) {
                    SendFcmNotification::dispatch($device->token, $message);
                }
            }
        });
    }

    private function buildMessage(Transaction $transaction, Accounts_transaction $accTrans): string
    {
        if ($transaction->type === 'transfer') {
            return $accTrans->sending_type === 'sender'
                ? "An amount of {$transaction->amount} has been debited from your account"
                : "An amount of {$transaction->amount} has been credited to your account";
        }

        return $transaction->type === 'deposit'
            ? "An amount of {$transaction->amount} has been credited to your account"
            : "An amount of {$transaction->amount} has been debited from your account";
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
