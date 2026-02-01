<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Loan periods (days)
            ['key' => 'loan_period_student', 'value' => '21', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Loan Period - Student', 'description' => 'Number of days a student can borrow items'],
            ['key' => 'loan_period_teacher', 'value' => '30', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Loan Period - Teacher', 'description' => 'Number of days a teacher can borrow items'],
            ['key' => 'loan_period_staff', 'value' => '30', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Loan Period - Staff', 'description' => 'Number of days a staff member can borrow items'],
            ['key' => 'loan_period_public', 'value' => '14', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Loan Period - Public', 'description' => 'Number of days a public member can borrow items'],

            // Max loans
            ['key' => 'max_loans_student', 'value' => '5', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Max Loans - Student', 'description' => 'Maximum items a student can borrow at once'],
            ['key' => 'max_loans_teacher', 'value' => '10', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Max Loans - Teacher', 'description' => 'Maximum items a teacher can borrow at once'],
            ['key' => 'max_loans_staff', 'value' => '10', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Max Loans - Staff', 'description' => 'Maximum items a staff member can borrow at once'],
            ['key' => 'max_loans_public', 'value' => '3', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Max Loans - Public', 'description' => 'Maximum items a public member can borrow at once'],

            // Fine settings
            ['key' => 'fine_per_day', 'value' => '0.50', 'type' => 'string', 'group' => 'fine', 'display_name' => 'Fine Per Day', 'description' => 'Amount charged per day for overdue items'],
            ['key' => 'grace_period_days', 'value' => '3', 'type' => 'integer', 'group' => 'fine', 'display_name' => 'Grace Period', 'description' => 'Days after due date before fines start'],
            ['key' => 'max_renewals', 'value' => '2', 'type' => 'integer', 'group' => 'loan', 'display_name' => 'Max Renewals', 'description' => 'Maximum number of times a loan can be renewed'],

            // Library info
            ['key' => 'library_name', 'value' => 'Library Management System', 'type' => 'string', 'group' => 'general', 'display_name' => 'Library Name', 'description' => 'Name of the library'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
