<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'customer'],
            ['role_name' => 'employee'],
            ['role_name' => 'manager'],
        ];

        foreach ($roles as $role) {
            Role::query()->create($role);
        }
    }
}
