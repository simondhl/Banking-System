<?php

namespace App\Models;

use App\Composite\AccountComponent;
use Illuminate\Database\Eloquent\Model;

class Account extends Model implements AccountComponent
{
    protected $fillable = ['user_id', 'account_type_id', 'account_hierarchy_id', 'account_status_id', 'account_number', 'balance', 'parent_account_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function account_type()
    {
        return $this->belongsTo(Account_type::class);
    }
    public function account_hierarchy()
    {
        return $this->belongsTo(Account_hierarchy::class);
    }
    public function account_status()
    {
        return $this->belongsTo(Account_status::class);
    }
    public function transaction()
    {
        return $this->belongsToMany(Transaction::class);
    }
    public function schedule_task()
    {
        return $this->belongsToMany(Schedule_task::class);
    }
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getAccountNumber()
    {
        return $this->account_number;
    }

    public function getChildren()
    {
        return $this->children;
    }
}
