<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts_transaction extends Model
{
    protected $fillable = ['account_id','transaction_id','sending_type'];
}
