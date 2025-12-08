<?php

namespace Database\Seeders;

use App\Models\Account_type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['type_name' => 'saving account'],
            ['type_name' => 'checking account'],
            ['type_name' => 'loan account'],
            ['type_name' => 'investment account'],
        ];

        foreach ($types as $type) {
            Account_type::query()->create($type);
        }
    }
}
