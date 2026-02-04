@extends('layouts.admin')

@section('content')
@php
    $value = function (string $key, $default = '') use ($settings) {
        return old($key, optional($settings->get($key))->value ?? $default);
    };
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
    <p class="text-gray-500 mt-1">Configure your library system</p>
</div>

@if($settings->isEmpty())
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-indigo-900">Initialize default settings</h2>
                <p class="text-sm text-indigo-700 mt-1">Load recommended defaults for loan rules and fines.</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.initialize') }}">
                @csrf
                <button type="submit" class="btn-primary btn-sm">Initialize</button>
            </form>
        </div>
    </div>
@endif

<!-- Tab Navigation -->
<div class="card">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
            <button type="button" data-tab="general" class="settings-tab active whitespace-nowrap px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                General
            </button>
            <button type="button" data-tab="loan-rules" class="settings-tab whitespace-nowrap px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                Loan Rules
            </button>
            <button type="button" data-tab="fines" class="settings-tab whitespace-nowrap px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                Fines
            </button>
            <button type="button" data-tab="payment-methods" class="settings-tab whitespace-nowrap px-6 py-4 border-b-2 font-medium text-sm transition-colors">
                Payment Methods
            </button>
        </nav>
    </div>

    <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <!-- General Tab -->
        <div id="general-tab" class="tab-content active">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-900">Library Profile</h3>
                    <p class="text-sm text-gray-500 mt-1">Basic information shown on receipts and communications</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="form-label">Library Name</label>
                        <input type="text" name="library_name" class="form-input"
                               value="{{ $value('library_name', 'Library Management System') }}"
                               placeholder="e.g., Central Public Library">
                        @error('library_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Email Address</label>
                            <input type="email" name="library_email" class="form-input"
                                   value="{{ $value('library_email') }}"
                                   placeholder="library@example.com">
                            @error('library_email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="library_phone" class="form-input"
                                   value="{{ $value('library_phone') }}"
                                   placeholder="+1 (555) 123-4567">
                            @error('library_phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Rules Tab -->
        <div id="loan-rules-tab" class="tab-content">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-900">Circulation Rules</h3>
                    <p class="text-sm text-gray-500 mt-1">Configure borrowing limits and loan periods</p>
                </div>

                <div class="space-y-8">
                    <!-- Loan Period -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Loan Period (Days)</h4>
                                <p class="text-xs text-gray-500 mt-1">How long members can keep borrowed items</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="form-label">Student</label>
                                <input type="number" min="1" max="365" name="loan_period_student" class="form-input"
                                       value="{{ $value('loan_period_student', 21) }}">
                                @error('loan_period_student')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Teacher</label>
                                <input type="number" min="1" max="365" name="loan_period_teacher" class="form-input"
                                       value="{{ $value('loan_period_teacher', 30) }}">
                                @error('loan_period_teacher')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Staff</label>
                                <input type="number" min="1" max="365" name="loan_period_staff" class="form-input"
                                       value="{{ $value('loan_period_staff', 30) }}">
                                @error('loan_period_staff')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Public</label>
                                <input type="number" min="1" max="365" name="loan_period_public" class="form-input"
                                       value="{{ $value('loan_period_public', 14) }}">
                                @error('loan_period_public')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Max Loans -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Maximum Loans</h4>
                                <p class="text-xs text-gray-500 mt-1">Items that can be borrowed simultaneously</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="form-label">Student</label>
                                <input type="number" min="1" max="20" name="max_loans_student" class="form-input"
                                       value="{{ $value('max_loans_student', 5) }}">
                                @error('max_loans_student')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Teacher</label>
                                <input type="number" min="1" max="20" name="max_loans_teacher" class="form-input"
                                       value="{{ $value('max_loans_teacher', 10) }}">
                                @error('max_loans_teacher')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Staff</label>
                                <input type="number" min="1" max="20" name="max_loans_staff" class="form-input"
                                       value="{{ $value('max_loans_staff', 10) }}">
                                @error('max_loans_staff')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Public</label>
                                <input type="number" min="1" max="20" name="max_loans_public" class="form-input"
                                       value="{{ $value('max_loans_public', 3) }}">
                                @error('max_loans_public')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Renewals -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Renewal Policy</h4>
                                <p class="text-xs text-gray-500 mt-1">How many times a loan can be extended</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Maximum Renewals Allowed</label>
                                <input type="number" min="0" max="10" name="max_renewals" class="form-input"
                                       value="{{ $value('max_renewals', 2) }}">
                                @error('max_renewals')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Set to 0 to disable renewals</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fines Tab -->
        <div id="fines-tab" class="tab-content">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-900">Fine Configuration</h3>
                    <p class="text-sm text-gray-500 mt-1">Set up late return penalties and grace periods</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Fine Per Day</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" min="0" max="100" step="0.01" name="fine_per_day" 
                                   class="form-input pl-7"
                                   value="{{ $value('fine_per_day', 0.50) }}">
                        </div>
                        @error('fine_per_day')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Amount charged for each day an item is overdue</p>
                    </div>

                    <div>
                        <label class="form-label">Grace Period (Days)</label>
                        <input type="number" min="0" max="30" name="grace_period_days" class="form-input"
                               value="{{ $value('grace_period_days', 3) }}">
                        @error('grace_period_days')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Days after due date before fines start</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-900 mb-2">How Fines Work</h5>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• Fines are calculated automatically after the grace period expires</li>
                        <li>• The daily fine rate applies to each overdue item separately</li>
                        <li>• Members cannot borrow new items if they have outstanding fines</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Payment Methods Tab -->
        <div id="payment-methods-tab" class="tab-content">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-base font-semibold text-gray-900">Payment Accounts</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage payment accounts for receiving library fines</p>
                </div>

                <!-- Information Box - Moved to Top -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h5 class="text-sm font-semibold text-blue-900">Dynamic Payment Accounts</h5>
                            <p class="text-sm text-blue-700 mt-1">
                                Add multiple payment accounts for different methods. Select the account type from the dropdown, 
                                then fill in the required details. Click "Save Changes" at the bottom to save all accounts.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6" id="payment-accounts-container">
                    <!-- Dynamic Accounts Container -->
                    <div id="accounts-list">
                        <!-- Accounts will be dynamically added here -->
                    </div>

                    <!-- Add Account Button -->
                    <div class="flex justify-center">
                        <button type="button" id="add-account-btn" class="btn-primary inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Payment Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3 bg-gray-50">
            <button type="submit" form="settings-form" class="btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.settings-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs
            tabs.forEach(t => {
                t.classList.remove('active', 'border-indigo-600', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active', 'border-indigo-600', 'text-indigo-600');
            this.classList.remove('border-transparent', 'text-gray-500');
            
            // Show corresponding content
            const targetContent = document.getElementById(targetTab + '-tab');
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
    
    // Handle validation errors - switch to tab with errors
    const errorElements = document.querySelectorAll('.text-red-600');
    if (errorElements.length > 0) {
        const firstError = errorElements[0];
        const errorTab = firstError.closest('.tab-content');
        if (errorTab) {
            const tabId = errorTab.id.replace('-tab', '');
            const tabButton = document.querySelector(`[data-tab="${tabId}"]`);
            if (tabButton) {
                tabButton.click();
            }
        }
    }

    // Dynamic Payment Accounts Functionality
    let accountCounter = 0;
    
    // Add account button
    document.getElementById('add-account-btn').addEventListener('click', function() {
        addNewAccount();
    });
    
    // Function to add new account form
    function addNewAccount(accountData = null) {
        accountCounter++;
        const accountId = `account-${accountCounter}`;
        
        const accountHtml = `
            <div class="card mb-6" id="${accountId}">
                <div class="card-header">
                    <h4 class="card-title">Payment Account #${accountCounter}</h4>
                    <button type="button" class="text-red-500 hover:text-red-700 remove-account-btn" data-account-id="${accountId}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label">Account Type</label>
                        <select name="accounts[${accountCounter}][type]" class="form-select account-type-select" data-account-id="${accountId}" required>
                            <option value="">Select Account Type</option>
                            <option value="mobile_banking" ${accountData && accountData.type === 'mobile_banking' ? 'selected' : ''}>Mobile Banking</option>
                            <option value="bank_account" ${accountData && accountData.type === 'bank_account' ? 'selected' : ''}>Bank Account</option>
                        </select>
                    </div>
                    
                    <div class="account-details" id="details-${accountId}">
                        ${getAccountDetailsHtml(accountData?.type || '', accountData, accountCounter)}
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('accounts-list').insertAdjacentHTML('beforeend', accountHtml);
        
        // Add event listener for type selection
        const selectElement = document.querySelector(`select.account-type-select[data-account-id="${accountId}"]`);
        selectElement.addEventListener('change', function() {
            const selectedType = this.value;
            const detailsContainer = document.getElementById(`details-${accountId}`);
            detailsContainer.innerHTML = getAccountDetailsHtml(selectedType, null, accountCounter);
        });
        
        // Add event listener for remove button
        document.getElementById(accountId).querySelector('.remove-account-btn').addEventListener('click', function() {
            document.getElementById(accountId).remove();
            updateAccountNumbers();
        });
    }
    
    // Function to get account details HTML based on type
    function getAccountDetailsHtml(type, accountData, counter) {
        if (type === 'mobile_banking') {
            return `
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Provider Name</label>
                        <input type="text" name="accounts[${counter}][provider]" class="form-input" 
                               value="${accountData?.provider || ''}" 
                               placeholder="e.g., bKash, Nagad, Rocket">
                        <p class="text-xs text-gray-500 mt-1">Enter the mobile banking provider name</p>
                    </div>
                    <div>
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="accounts[${counter}][mobile_number]" class="form-input" 
                               value="${accountData?.mobile_number || ''}" 
                               placeholder="e.g., 01XXXXXXXXX" required>
                        <p class="text-xs text-gray-500 mt-1">Members will send payments to this number</p>
                    </div>
                </div>
            `;
        } else if (type === 'bank_account') {
            return `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="accounts[${counter}][bank_name]" class="form-input" 
                                   value="${accountData?.bank_name || ''}" 
                                   placeholder="e.g., Dutch-Bangla Bank" required>
                        </div>
                        <div>
                            <label class="form-label">Account Number</label>
                            <input type="text" name="accounts[${counter}][account_number]" class="form-input" 
                                   value="${accountData?.account_number || ''}" 
                                   placeholder="e.g., 1234567890" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Account Holder Name</label>
                            <input type="text" name="accounts[${counter}][account_name]" class="form-input" 
                                   value="${accountData?.account_name || ''}" 
                                   placeholder="e.g., Library Management System" required>
                        </div>
                        <div>
                            <label class="form-label">Branch Name (Optional)</label>
                            <input type="text" name="accounts[${counter}][branch_name]" class="form-input" 
                                   value="${accountData?.branch_name || ''}" 
                                   placeholder="e.g., Gulshan Branch">
                        </div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-700">
                            <strong>Note:</strong> Members can use these details to deposit or transfer money to your library account.
                        </p>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <p class="text-gray-600 font-medium">Select an account type above</p>
                    <p class="text-sm text-gray-500 mt-1">Choose Mobile Banking or Bank Account to continue</p>
                </div>
            `;
        }
    }
    
    // Function to update account numbers after removal
    function updateAccountNumbers() {
        const accounts = document.querySelectorAll('#accounts-list .card');
        accounts.forEach((account, index) => {
            const title = account.querySelector('.card-title');
            title.textContent = `Payment Account #${index + 1}`;
        });
    }
    
    // Load existing accounts if any (this would come from backend)
    // For demo purposes, adding one empty account
    const existingAccounts = @json($settings->filter(function($setting) {
        return str_starts_with($setting->key, 'payment_account_');
    })->values());
    
    if (existingAccounts.length > 0) {
        // Group accounts by their index
        const accountsMap = {};
        existingAccounts.forEach(setting => {
            const match = setting.key.match(/payment_account_(\d+)_(.+)/);
            if (match) {
                const index = match[1];
                const field = match[2];
                if (!accountsMap[index]) {
                    accountsMap[index] = {};
                }
                accountsMap[index][field] = setting.value;
            }
        });
        
        // Add each account
        Object.values(accountsMap).forEach(accountData => {
            addNewAccount(accountData);
        });
    } else {
        // Add one empty account if none exist
        addNewAccount();
    }
});
</script>

<style>
.settings-tab {
    border-color: transparent;
    color: #6B7280;
}

.settings-tab:hover {
    color: #374151;
    border-color: #D1D5DB;
}

.settings-tab.active {
    border-color: #4F46E5;
    color: #4F46E5;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>
@endpush
@endsection
