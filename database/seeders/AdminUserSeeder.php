<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@dcs.com.na',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'password_changed_at' => now(),
        ]);

        // Create a risk manager user
        User::create([
            'name' => 'Risk Manager',
            'email' => 'manager@dcs.com.na',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'is_active' => true,
            'email_verified_at' => now(),
            'password_changed_at' => now(),
        ]);

        // Create a risk analyst user
        User::create([
            'name' => 'Risk Analyst',
            'email' => 'analyst@dcs.com.na',
            'password' => Hash::make('analyst123'),
            'role' => 'staff',
            'is_active' => true,
            'email_verified_at' => now(),
            'password_changed_at' => now(),
        ]);
    }
}
