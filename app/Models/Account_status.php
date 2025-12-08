<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_status extends Model
{
    protected $fillable = ['status'];

    public function account(){
        return $this->hasMany(Account::class);
    }
}
