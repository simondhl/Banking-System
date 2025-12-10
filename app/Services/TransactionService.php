<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Accounts_transaction;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{

  public function deposit_or_withdrawal(array $request)
  {

    $account = Account::where('account_number', ($request['account_number']))->first();

    $amount = $request['amount'];
    $type = strtolower($request['transaction_type']);

    // validate if account able to do the transaction
    $validation = $this->validate_account_for_transaction($account, $type, $amount);

    if (!$validation['success']) {
        return $validation; 
    }

    // to make sure all transaction steps happened as a one block (ACID)
    DB::transaction(function () use ($account, $amount, $type) {
    // apply transaction
      if ($type === 'deposit') {
          $account->balance += $amount;
      }
    
      if ($type === 'withdrawal') {
          $account->balance -= $amount;
      }
    
      $account->save();
    
      $transaction = Transaction::create([
          'type'   => $type,
          'amount' => $amount,
          // 'status' => true,
      ]);
      $accounts_transaction = Accounts_transaction::create([
          'account_id'   => $account->id,
          'transaction_id' => $transaction->id,
          'sending_type' => $type,
      ]);
    });

    return [
      'success' => true,
      'message' => 'Transaction successful',
    ];
  }

  private function validate_account_for_transaction(Account $account,string $operationType, float $amount)
  {
      // check the status of the account
      $invalidStatuses = ['frozen', 'suspended', 'closed'];
      $account_status = $account->account_status->status;
      
      if (in_array($account_status, $invalidStatuses)) {
          return [
            'success' => false,
            'message' => "This account is {$account_status}, transactions are not allowed"
        ];
      }

      // check if have enough money if it was (withdrawal or sender transfer).
      if (in_array($operationType, ['withdrawal', 'transfer_sender'])) {
          if ($account->balance < $amount) {
            return [
              'success' => false,
              'message' => "You do not have enough balance in your account"
            ];
          }
      }
      return ['success' => true];
  }


  public function transfer(array $request)
  {

    $account_sender = Account::where('account_number', ($request['account_number_sender']))->first();
    $account_reciever = Account::where('account_number', ($request['account_number_reciever']))->first();

    $amount = $request['amount'];

    // validate if both accounts are able to do the transaction (sender and receiver)
    $validation = $this->validate_account_for_transaction($account_sender, 'transfer_sender', $amount);
    if (!$validation['success']) {
        return $validation; 
    }

    $validation = $this->validate_account_for_transaction($account_reciever, 'transfer_reciever', $amount);
    if (!$validation['success']) {
        return $validation; 
    }

    // to make sure all transaction steps happened as a one block (ACID)
    DB::transaction(function () use ($account_sender, $account_reciever, $amount) {
      // apply transfere transaction
      $account_sender->balance -= $amount;
      $account_reciever->balance += $amount;

      $account_sender->save();
      $account_reciever->save();

      $transaction = Transaction::create([
          'type'   => 'transfer',
          'amount' => $amount,
          // 'status' => true,
      ]);
      $accounts_transaction = Accounts_transaction::create([
          'account_id'   => $account_sender->id,
          'transaction_id' => $transaction->id,
          'sending_type' => 'sender',
      ]);
      $accounts_transaction = Accounts_transaction::create([
          'account_id'   => $account_reciever->id,
          'transaction_id' => $transaction->id,
          'sending_type' => 'receiver',
      ]);
    });

    return [
      'success' => true,
      'message' => 'Transaction successful',
    ];
  }


}
