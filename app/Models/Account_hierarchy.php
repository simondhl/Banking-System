<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_hierarchy extends Model
{
    protected $fillable = ['hierarchy_name'];

    public function account(){
        return $this->hasMany(Account::class);
    }
}
