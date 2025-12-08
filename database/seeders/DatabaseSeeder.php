<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AccountHierarchySeeder::class);
        $this->call(AccountTypeSeeder::class);
        $this->call(AccountStatusSeeder::class);
        $this->call(UserSeeder::class);
    }
}
