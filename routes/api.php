<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/Login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

  Route::get('/Logout', [AuthController::class, 'logout']);

  // Customer Routes
  Route::middleware('role:customer')->group(function () {
  });

  // Employee Routes
  Route::middleware('role:employee')->group(function () {
        Route::post('/CreateAccount', [AccountController::class, 'create_account']);
        Route::post('/CloseAccount/{id}', [AccountController::class, 'close_account']);
        Route::post('/SearchAccount',[AccountController::class,'search_account']);
    });

  // Manager Routes
  Route::middleware('role:manager')->group(function () {

  });

});
