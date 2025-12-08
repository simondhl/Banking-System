<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
          'user_number' => '120300999',
          'password' => '123sS123##',
          'phone_number' => '0937523553',
          'national_number' => '1202010561',
          'first_name' => 'manager',
          'last_name' => 'manager',
          'location' => 'Damascus',
          'role_id' => '3',
        ]);
        User::query()->create([
          'user_number' => '120300888',
          'password' => '123sS123##',
          'phone_number' => '091238791',
          'national_number' => '1202001562',
          'first_name' => 'employee1',
          'last_name' => 'employee1',
          'location' => 'Damascus',
          'role_id' => '2',
        ]);
        User::query()->create([
          'user_number' => '120300777',
          'password' => '123sS123##',
          'phone_number' => '0937523553',
          'national_number' => '1202002498',
          'first_name' => 'employee2',
          'last_name' => 'employee2',
          'location' => 'Damascus',
          'role_id' => '2',
        ]);

    }
}
