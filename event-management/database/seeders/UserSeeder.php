<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '555123456',
        ]);

        // Organizer
        User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'phone' => '555234567',
        ]);

        // Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '555345678',
        ]);
    }
}