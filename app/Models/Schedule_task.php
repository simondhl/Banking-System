<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule_task extends Model
{
   protected $fillable = ['type','amount','date'];

   public function account(){
      return $this->belongsToMany(Account::class);
   }
}
