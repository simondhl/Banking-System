<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'national_number',
        'user_number',
        'password',
        'phone_number',
        'first_name',
        'last_name',
        'location',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function role(){
       return $this->belongsTo(Role::class);
    }
    public function device_token()
    {
        return $this->hasMany(Device_token::class);
    }
    public function inquiry(){
        return $this->hasMany(Inquiry::class);
    }
    public function account(){
        return $this->hasOne(Account::class);
    }
}
