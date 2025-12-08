<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_type extends Model
{
    protected $fillable = ['type_name'];

    public function account(){
        return $this->hasMany(Account::class);
    }
}
