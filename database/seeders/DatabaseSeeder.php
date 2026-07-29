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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'nip' => '123456789',
            'department' => 'IT',
            'position' => 'Manager',
            'allowance_id' => '2',
            'compensation_id' => '2',
            
        ]);
        User::factory()->create([
            'name' => 'Another Test User',
            'email' => 'another.test@example.com',
            'password' => bcrypt('password'),
            'nip' => '987654321',
            'department' => 'HR',
            'position' => 'Staff',
            'allowance_id' => '1',
            'compensation_id' => '1',
        ]);
    }
}
