<?php

namespace App\Models;

use App\Composite\AccountComponent;
use App\States\AccountStateInterface;
use App\States\ActiveState;
use App\States\ClosedState;
use App\States\FrozenState;
use App\States\SuspendedState;
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

    public function getState()
    {
        return match ($this->account_status_id) {
            1 => new ActiveState(),
            2 => new FrozenState(),
            3 => new SuspendedState(),
            4 => new ClosedState(),
            default => new ActiveState(),
        };
    }
    public function close()
    {
        return $this->getState()->close($this);
    }

    public function freeze()
    {
        return $this->getState()->freeze($this);
    }

    public function suspend()
    {
        return $this->getState()->suspend($this);
    }

    public function activate()
    {
        return $this->getState()->activate($this);
    }
}
