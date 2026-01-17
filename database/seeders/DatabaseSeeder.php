<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin'
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        // Create Staff
        User::create([
            'name' => 'Staff',
            'email' => 'staff@notarybot.com',
            'password' => Hash::make('password123'),
            'role' => 'staff'
        ]);

        // Call other seeders
        $this->call([
            QuickResponseSeeder::class,
        ]);
    }
}
