<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id','account_type_id','account_hierarchy_id','account_status_id','account_number','balance'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function account_type(){
        return $this->belongsTo(Account_type::class);
    }
    public function account_hierarchy(){
        return $this->belongsTo(Account_hierarchy::class);
    }
    public function account_status(){
        return $this->belongsTo(Account_status::class);
    }
    public function transaction(){
        return $this->belongsToMany(Transaction::class);
    }
    public function schedule_task(){
        return $this->belongsToMany(Schedule_task::class);
    }
}
