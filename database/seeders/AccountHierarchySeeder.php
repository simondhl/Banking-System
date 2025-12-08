<?php

namespace Database\Seeders;

use App\Models\Account_hierarchy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hierarchies = [
            ['hierarchy_name' => 'individual account'],
            ['hierarchy_name' => 'group account'],
        ];

        foreach ($hierarchies as $hierarchy) {
            Account_hierarchy::query()->create($hierarchy);
        }
    }
}
