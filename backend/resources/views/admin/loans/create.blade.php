@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.loans.index') }}" class="hover:text-gray-700">Loans</a>
        <span>/</span>
        <span>New Loan</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">New Loan</h1>
    <p class="text-gray-500 mt-1">Checkout an item to a member</p>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.loans.store') }}" class="card">
        @csrf

        <div class="card-body space-y-6">
            <!-- Member Code -->
            <div>
                <label class="form-label">Member Code <span class="text-red-500">*</span></label>
                <input type="text" name="member_code" class="form-input" required
                       value="{{ old('member_code') }}" placeholder="e.g. MEM202500001">
                @error('member_code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Barcode -->
            <div>
                <label class="form-label">Copy Barcode <span class="text-red-500">*</span></label>
                <input type="text" name="barcode" class="form-input" required
                       value="{{ old('barcode') }}" placeholder="e.g. 10001">
                @error('barcode')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Loan Period (Optional) -->
            <div>
                <label class="form-label">Loan Period (Days)</label>
                <input type="number" name="loan_period_days" class="form-input"
                       value="{{ old('loan_period_days') }}" min="1" max="365" placeholder="Leave empty for default">
                @error('loan_period_days')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">
                    Default loan periods: Students (14 days), Teachers (30 days), Staff (14 days), Public (7 days)
                </p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.loans.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Loan</button>
        </div>
    </form>

    <!-- Quick Reference -->
    <div class="card mt-6">
        <div class="card-header">
            <h2 class="card-title">Quick Reference</h2>
        </div>
        <div class="card-body text-sm space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-500">Default Loan Period:</span>
                <span class="font-medium">14 days (varies by member type)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Max Renewals:</span>
                <span class="font-medium">2 times</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Fine per Day:</span>
                <span class="font-medium">$0.50</span>
            </div>
        </div>
    </div>
</div>
@endsection
