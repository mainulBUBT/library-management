<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffMembers = [
            [
                'name' => 'Alice Cooper',
                'email' => 'alice.cooper@library.com',
                'phone' => '+1-555-1001',
                'position' => 'Librarian',
                'role' => 'librarian',
                'department' => 'Circulation',
            ],
            [
                'name' => 'Benjamin Carter',
                'email' => 'benjamin.carter@library.com',
                'phone' => '+1-555-1002',
                'position' => 'Senior Librarian',
                'role' => 'librarian',
                'department' => 'Reference',
            ],
            [
                'name' => 'Carolyn Davis',
                'email' => 'carolyn.davis@library.com',
                'phone' => '+1-555-1003',
                'position' => 'Library Assistant',
                'role' => 'assistant_librarian',
                'department' => 'Circulation',
            ],
            [
                'name' => 'Daniel Evans',
                'email' => 'daniel.evans@library.com',
                'phone' => '+1-555-1004',
                'position' => 'Cataloger',
                'role' => 'assistant_librarian',
                'department' => 'Technical Services',
            ],
            [
                'name' => 'Elizabeth Foster',
                'email' => 'elizabeth.foster@library.com',
                'phone' => '+1-555-1005',
                'position' => 'Children\'s Librarian',
                'role' => 'librarian',
                'department' => 'Children\'s Services',
            ],
        ];

        foreach ($staffMembers as $staffData) {
            // Create user account
            $user = User::firstOrCreate(
                ['email' => $staffData['email']],
                [
                    'name' => $staffData['name'],
                    'password' => Hash::make('password'),
                    'role' => $staffData['role'],
                    'phone' => $staffData['phone'],
                ]
            );

            // Create staff profile
            Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => $this->generateEmployeeId(),
                    'joined_date' => now()->subDays(rand(60, 1000)),
                    'employment_status' => 'active',
                ]
            );
        }
    }

    /**
     * Generate a unique employee ID.
     */
    private function generateEmployeeId(): string
    {
        static $counter = 2;
        return 'EMP' . str_pad($counter++, 4, '0', STR_PAD_LEFT);
    }
}
