<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin
        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Staff
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $this->command->info('Default users created successfully!');
        $this->command->info('Superadmin: superadmin@notarybot.com / password123');
        $this->command->info('Admin: admin@notarybot.com / password123');
        $this->command->info('Staff: staff@notarybot.com / password123');
    }
}
