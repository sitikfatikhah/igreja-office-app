<?php

namespace Database\Seeders;

use App\Models\Positions;
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

        User::updateOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'nip' => '123456789',
            'department' => 'IT',
            'position' => 'Manager',
            'allowance_id' => 2,
            'compensation_id' => 2,
        ]
    );
    User::updateOrCreate(
        ['email' => 'test@example2.com'],
        [
            'name' => 'User',
            'password' => bcrypt('password'),
            'nip' => '123456789',
            'department' => 'HR',
            'position' => 'Staff',
            'allowance_id' => 1,
            'compensation_id' => 1,
        ]
    );
    }
}
