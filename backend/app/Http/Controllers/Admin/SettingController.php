<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get all settings grouped by group
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Loan settings
            'loan_period_student' => 'nullable|integer|min:1|max:365',
            'loan_period_teacher' => 'nullable|integer|min:1|max:365',
            'loan_period_staff' => 'nullable|integer|min:1|max:365',
            'loan_period_public' => 'nullable|integer|min:1|max:365',
            'max_loans_student' => 'nullable|integer|min:1|max:20',
            'max_loans_teacher' => 'nullable|integer|min:1|max:20',
            'max_loans_staff' => 'nullable|integer|min:1|max:20',
            'max_loans_public' => 'nullable|integer|min:1|max:20',
            'max_renewals' => 'nullable|integer|min:0|max:10',

            // Fine settings
            'fine_per_day' => 'nullable|numeric|min:0|max:100',
            'grace_period_days' => 'nullable|integer|min:0|max:30',

            // Library info
            'library_name' => 'nullable|string|max:255',
            'library_email' => 'nullable|email|max:255',
            'library_phone' => 'nullable|string|max:20',

            // Payment accounts
            'accounts' => 'nullable|array',
            'accounts.*.type' => 'required|in:mobile_banking,bank_account',
            'accounts.*.provider' => 'nullable|string|max:255',
            'accounts.*.mobile_number' => 'nullable|string|max:20',
            'accounts.*.bank_name' => 'nullable|string|max:255',
            'accounts.*.account_number' => 'nullable|string|max:255',
            'accounts.*.account_name' => 'nullable|string|max:255',
            'accounts.*.branch_name' => 'nullable|string|max:255',
        ]);

        // Update regular settings
        foreach ($validated as $key => $value) {
            if ($value !== null && $key !== 'accounts') {
                Setting::set($key, $value);
            }
        }

        // Delete old payment accounts
        Setting::where('key', 'like', 'payment_account_%')->delete();

        // Save payment accounts
        if (!empty($validated['accounts'])) {
            foreach ($validated['accounts'] as $index => $account) {
                $accountIndex = $index + 1;
                
                // Save account type
                Setting::set("payment_account_{$accountIndex}_type", $account['type']);
                
                if ($account['type'] === 'mobile_banking') {
                    if (!empty($account['provider'])) {
                        Setting::set("payment_account_{$accountIndex}_provider", $account['provider']);
                    }
                    if (!empty($account['mobile_number'])) {
                        Setting::set("payment_account_{$accountIndex}_mobile_number", $account['mobile_number']);
                    }
                } elseif ($account['type'] === 'bank_account') {
                    if (!empty($account['bank_name'])) {
                        Setting::set("payment_account_{$accountIndex}_bank_name", $account['bank_name']);
                    }
                    if (!empty($account['account_number'])) {
                        Setting::set("payment_account_{$accountIndex}_account_number", $account['account_number']);
                    }
                    if (!empty($account['account_name'])) {
                        Setting::set("payment_account_{$accountIndex}_account_name", $account['account_name']);
                    }
                    if (!empty($account['branch_name'])) {
                        Setting::set("payment_account_{$accountIndex}_branch_name", $account['branch_name']);
                    }
                }
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Initialize default settings.
     */
    public function initialize()
    {
        $defaults = [
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

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        return back()->with('success', 'Default settings initialized.');
    }
}
