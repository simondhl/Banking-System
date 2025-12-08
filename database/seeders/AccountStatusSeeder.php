<?php

namespace Database\Seeders;

use App\Models\Account_status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status' => 'active'],
            ['status' => 'frozen'],
            ['status' => 'suspended'],
            ['status' => 'closed'],
        ];

        foreach ($statuses as $status) {
            Account_status::query()->create($status);
        }  
    }
}
