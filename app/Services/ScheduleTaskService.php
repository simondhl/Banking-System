<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Accounts_schedule_task;
use App\Models\Schedule_task;
use App\Services\TransactionApprovalChain\AutoApprovalHandler;
use App\Services\TransactionApprovalChain\ManagerApprovalHandler;
use Illuminate\Support\Facades\DB;

class ScheduleTaskService
{

  public function deposit_or_withdrawal_schedule(array $request)
  {

    $account = Account::where('account_number', ($request['account_number']))->first();

    $amount = $request['amount'];
    $type = strtolower($request['transaction_type']);
    $date = $request['date'];

    // validate if account able to do the transaction
    $validation = $this->validate_account_for_transaction($account, $type, $amount);

    if (!$validation['success']) {
        return $validation; 
    }

    // auto approval or it needs a higher approval
    $approval = $this->runApprovalChain($amount, auth()->user());
    if (!$approval['approved']) {
        return [
            'success' => false,
            'message' => $approval['message']
        ];
    }

    // to make sure all transaction steps happened as a one block (ACID)
    DB::transaction(function () use ($account, $amount, $type, $date) {
    
      $schedule_task = Schedule_task::create([
          'type'   => $type,
          'amount' => $amount,
          'date' => $date,
      ]);
      $account_schedule_task = Accounts_schedule_task::create([
          'account_id'   => $account->id,
          'schedule_task_id' => $schedule_task->id,
          'sending_type' => $type,
      ]);
    });

    return [
      'success' => true,
      'message' => 'Scheduled transaction successful',
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
            'message' => "This account is {$account_status}, scheduled transactions are not allowed"
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


  public function transfer_schedule(array $request)
  {

    $account_sender = Account::where('account_number', ($request['account_number_sender']))->first();
    $account_reciever = Account::where('account_number', ($request['account_number_reciever']))->first();

    $amount = $request['amount'];
    $date = $request['date'];

    // validate if both accounts are able to do the transaction (sender and receiver)
    $validation = $this->validate_account_for_transaction($account_sender, 'transfer_sender', $amount);
    if (!$validation['success']) {
        return $validation; 
    }

    $validation = $this->validate_account_for_transaction($account_reciever, 'transfer_reciever', $amount);
    if (!$validation['success']) {
        return $validation; 
    }

    // auto approval or it needs a higher approval
    $approval = $this->runApprovalChain($amount, auth()->user());
    if (!$approval['approved']) {
        return [
            'success' => false,
            'message' => $approval['message']
        ];
    }

    // to make sure all transaction steps happened as a one block (ACID)
    DB::transaction(function () use ($account_sender, $account_reciever, $amount, $date) {

      $schedule_task = Schedule_task::create([
          'type'   => 'transfer',
          'amount' => $amount,
          'date' => $date,
      ]);
      $account_schedule_task = Accounts_schedule_task::create([
          'account_id'   => $account_sender->id,
          'schedule_task_id' => $schedule_task->id,
          'sending_type' => 'sender',
      ]);
      $account_schedule_task = Accounts_schedule_task::create([
          'account_id'   => $account_reciever->id,
          'schedule_task_id' => $schedule_task->id,
          'sending_type' => 'receiver',
      ]);
    });

    return [
      'success' => true,
      'message' => 'Scheduled transaction successful',
    ];
  }


  private function runApprovalChain($amount, $user)
  {
    $auto = new AutoApprovalHandler();
    $manager = new ManagerApprovalHandler();

    // Build the chain of responsibility
    $auto->setNext($manager);

    return $auto->handle($amount, $user);
  }

}
