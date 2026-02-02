@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.fines.index') }}" class="hover:text-gray-700">Fines</a>
        <span>/</span>
        <span>Add Fine</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Add Manual Fine</h1>
    <p class="text-gray-500 mt-1">Create a manual fine for a member</p>
</div>

<div class="w-full">
    <div class="mx-auto w-full max-w-4xl">
    <form method="POST" action="{{ route('admin.fines.store') }}" class="card">
        @csrf

        <div class="card-body space-y-6">
            <!-- Member Code & Amount -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Member Code <span class="text-red-500">*</span></label>
                    <input type="text" name="member_code" class="form-input" required
                           value="{{ old('member_code') }}" placeholder="e.g. MEM202500001">
                    @error('member_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Amount ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" class="form-input" required
                           value="{{ old('amount') }}" min="0.01" step="0.01" placeholder="0.00">
                    @error('amount')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label class="form-label">Reason <span class="text-red-500">*</span></label>
                <input type="text" name="reason" class="form-input" required
                       value="{{ old('reason') }}" placeholder="e.g. Lost book, Damaged resource">
                @error('reason')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="3" class="form-textarea"
                          placeholder="Optional notes...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.fines.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Fine</button>
        </div>
    </form>
    </div>
</div>
@endsection
