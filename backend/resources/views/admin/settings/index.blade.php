@extends('layouts.admin')

@section('content')
@php
    $value = function (string $key, $default = '') use ($settings) {
        return old($key, optional($settings->get($key))->value ?? $default);
    };
@endphp

<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-gray-500 mt-1">Configure your library system</p>
    </div>
    <button type="submit" form="settings-form" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Save Changes
    </button>
</div>

@if($settings->isEmpty())
    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between gap-4">
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
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                        <label class="form-label">Fine Per Day ($)</label>
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
                    <h3 class="text-base font-semibold text-gray-900">Payment Configuration</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage how your library accepts fine payments</p>
                </div>

                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 mb-2">Offline Payment Tracking</h4>
                    <p class="text-sm text-gray-600 max-w-lg mx-auto mb-6">
                        Currently, the system tracks offline payments (cash, check, bank transfer) made at the library desk. 
                        Payment gateway integration for online payments is planned for future releases.
                    </p>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Coming in Future Updates
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-6 border-t border-gray-200">
                    <div>
                        <h5 class="text-sm font-semibold text-gray-900 mb-3">Currently Supported</h5>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Cash payments
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Check payments
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Bank transfers
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Payment receipts
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="text-sm font-semibold text-gray-900 mb-3">Planned Features</h5>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Stripe integration
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                PayPal support
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Online payment portal
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Automated receipts
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 flex items-center justify-end gap-3 bg-gray-50">
            <button type="submit" class="btn-primary">Save All Settings</button>
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
