<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounts_schedule_task extends Model
{
    protected $fillable = ['account_id','schedule_task_id','sending_type'];
}
