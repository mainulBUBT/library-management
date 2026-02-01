<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '+1234567890',
            ]
        );

        // Create Staff Profile
        Staff::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'EMP001',
                'joined_date' => now(),
                'employment_status' => 'active',
            ]
        );
    }
}
