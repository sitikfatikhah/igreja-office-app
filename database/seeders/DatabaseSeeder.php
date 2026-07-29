<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat role Super Admin jika belum ada
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'nip' => '12345',
                'department' => 'IT',
                'position' => 'Manager',
                'allowance_id' => 2,
                'compensation_id' => 2,
            ]
        );

        // Assign role Super Admin
        $admin->syncRoles([$superAdminRole]);

        // User biasa
        $user = User::updateOrCreate(
            ['email' => 'test@example2.com'],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
                'nip' => '67891',
                'department' => 'HR',
                'position' => 'Staff',
                'allowance_id' => 1,
                'compensation_id' => 1,
            ]
        );

        // Hapus role jika ada (opsional)
        $user->syncRoles([]);
    }
}