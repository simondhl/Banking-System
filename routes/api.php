<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ScheduleTaskController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/Login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/Logout', [AuthController::class, 'logout']);

    // Customer Routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/GetTransactionsForCustomar', [TransactionController::class, 'get_transactions_for_customer']);
        Route::post('/SendInquiry', [InquiryController::class, 'send_inquiry']);
    });

    // Employee Routes
    Route::middleware('role:employee')->group(function () {
        Route::post('/CreateAccount', [AccountController::class, 'create_account']);
        Route::get('/CloseAccount/{id}', [AccountController::class, 'close_account']);
        Route::post('/SearchAccount', [AccountController::class, 'search_account']);
        Route::post('/UpdateAccount/{id}', [AccountController::class, 'update_account']);
        //Transactions: Deposis-withdrawal, Transfer between accounts
        Route::post('/DepositOrWithdrawal', [TransactionController::class, 'deposit_or_withdrawal']);
        Route::post('/Transfer', [TransactionController::class, 'transfer']);
        //Scheduled Transactions
        Route::post('/DepositOrWithdrawalSchedule', [ScheduleTaskController::class, 'deposit_or_withdrawal_schedule']);
        Route::post('/TransferSchedule', [ScheduleTaskController::class, 'transfer_schedule']);

        Route::post('/GetTransactionsForEmployee', [TransactionController::class, 'get_transactions_for_employee']);
    });

    // Manager Routes
    Route::middleware('role:manager')->group(function () {
        Route::get('/GetInquiries', [InquiryController::class, 'get_inquiries']);

        //Transactions: Deposis-withdrawal, Transfer between accounts
        // Route::post('/DepositOrWithdrawal', [TransactionController::class, 'deposit_or_withdrawal']);
        // Route::post('/Transfer', [TransactionController::class, 'transfer']);
        Route::post('/CreateEmployee', [UserController::class, 'create_employee']);
    });
});
