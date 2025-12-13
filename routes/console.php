<?php

use App\Models\Account;
use App\Models\Accounts_schedule_task;
use App\Models\Schedule_task;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schedule;


Schedule::call(function () {

    $tasks = Schedule_task::where('date', '<=', Carbon::today())->get();

    $transactionService = app(TransactionService::class);

    foreach ($tasks as $task) {

        $type = strtolower($task->type);

        if (in_array($type, ['deposit', 'withdrawal'])) {

            $links = Accounts_schedule_task::where('schedule_task_id', $task->id)->first();
            $account = Account::where('id', $links->account_id)->first();

            $transactionService->deposit_or_withdrawal([
                'transaction_type' => $type,
                'amount' => $task->amount,
                'account_number' => $account->account_number,
                'from_schedule' => true
            ]);
        }

        if ($type === 'transfer') {

            $links = Accounts_schedule_task::where('schedule_task_id', $task->id)->get();
            $sender_link = $links->where('sending_type', 'sender')->first();
            $receiver_link = $links->where('sending_type', 'receiver')->first();

            $sender = Account::where('id', $sender_link->account_id)->first();
            $receiver = Account::where('id', $receiver_link->account_id)->first();
            // $sender = $links->where('sending_type', 'sender')->first()->account;
            // $receiver = $links->where('sending_type', 'receiver')->first()->account;

            $transactionService->transfer([
                'amount' => $task->amount,
                'account_number_sender' => $sender->account_number,
                'account_number_reciever' => $receiver->account_number,
                'from_schedule' => true
            ]);
        }

        // update for next transaction (next month)
        $task->date = Carbon::parse($task->date)->addMonth();
        $task->save();
    }

})->everyMinute();
