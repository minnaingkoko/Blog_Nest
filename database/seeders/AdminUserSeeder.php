<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'Administrator'
        ]);

        // Create admin user
        User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $adminRole->id
        ]);

        // Optional: Output confirmation
        $this->command->info('Admin user created: admin@example.com / password');
    }
}