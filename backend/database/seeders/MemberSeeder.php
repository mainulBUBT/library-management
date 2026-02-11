<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1-555-0101',
                'address' => '123 Main St, Springfield, IL 62701',
                'member_type' => 'student',
            ],
            [
                'name' => 'Emily Johnson',
                'email' => 'emily.j@example.com',
                'phone' => '+1-555-0102',
                'address' => '456 Oak Ave, Springfield, IL 62702',
                'member_type' => 'teacher',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.b@example.com',
                'phone' => '+1-555-0103',
                'address' => '789 Pine Rd, Springfield, IL 62703',
                'member_type' => 'student',
            ],
            [
                'name' => 'Sarah Davis',
                'email' => 'sarah.d@example.com',
                'phone' => '+1-555-0104',
                'address' => '321 Elm St, Springfield, IL 62704',
                'member_type' => 'public',
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.w@example.com',
                'phone' => '+1-555-0105',
                'address' => '654 Maple Dr, Springfield, IL 62705',
                'member_type' => 'student',
            ],
            [
                'name' => 'Jessica Martinez',
                'email' => 'jessica.m@example.com',
                'phone' => '+1-555-0106',
                'address' => '987 Cedar Ln, Springfield, IL 62706',
                'member_type' => 'teacher',
            ],
            [
                'name' => 'Robert Anderson',
                'email' => 'robert.a@example.com',
                'phone' => '+1-555-0107',
                'address' => '147 Birch Blvd, Springfield, IL 62707',
                'member_type' => 'public',
            ],
            [
                'name' => 'Lisa Taylor',
                'email' => 'lisa.t@example.com',
                'phone' => '+1-555-0108',
                'address' => '258 Walnut Way, Springfield, IL 62708',
                'member_type' => 'student',
            ],
            [
                'name' => 'James Thomas',
                'email' => 'james.t@example.com',
                'phone' => '+1-555-0109',
                'address' => '369 Cherry Ct, Springfield, IL 62709',
                'member_type' => 'staff',
            ],
            [
                'name' => 'Jennifer White',
                'email' => 'jennifer.w@example.com',
                'phone' => '+1-555-0110',
                'address' => '741 Spruce St, Springfield, IL 62710',
                'member_type' => 'student',
            ],
            [
                'name' => 'Daniel Harris',
                'email' => 'daniel.h@example.com',
                'phone' => '+1-555-0111',
                'address' => '852 Aspen Ave, Springfield, IL 62711',
                'member_type' => 'public',
            ],
            [
                'name' => 'Amanda Clark',
                'email' => 'amanda.c@example.com',
                'phone' => '+1-555-0112',
                'address' => '963 Willow Rd, Springfield, IL 62712',
                'member_type' => 'teacher',
            ],
            [
                'name' => 'Christopher Lewis',
                'email' => 'christopher.l@example.com',
                'phone' => '+1-555-0113',
                'address' => '174 Oak St, Springfield, IL 62713',
                'member_type' => 'student',
            ],
            [
                'name' => 'Michelle Robinson',
                'email' => 'michelle.r@example.com',
                'phone' => '+1-555-0114',
                'address' => '285 Pine Ave, Springfield, IL 62714',
                'member_type' => 'public',
            ],
            [
                'name' => 'Kevin Walker',
                'email' => 'kevin.w@example.com',
                'phone' => '+1-555-0115',
                'address' => '396 Elm Dr, Springfield, IL 62715',
                'member_type' => 'student',
            ],
        ];

        foreach ($members as $memberData) {
            // Create user account
            $user = User::firstOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'member',
                    'phone' => $memberData['phone'],
                    'address' => $memberData['address'],
                ]
            );

            // Create member profile
            Member::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'member_code' => $this->generateMemberCode(),
                    'member_type' => $memberData['member_type'],
                    'joined_date' => now()->subDays(rand(30, 500)),
                    'status' => 'active',
                    'expiry_date' => now()->addYear(),
                ]
            );
        }
    }

    /**
     * Generate a unique member code.
     */
    private function generateMemberCode(): string
    {
        return 'MEM-' . strtoupper(uniqid());
    }
}
