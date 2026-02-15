@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.reservations.index') }}" class="hover:text-gray-700">Reservations</a>
        <span>/</span>
        <span>New Reservation</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">New Reservation</h1>
    <p class="text-gray-500 mt-1">Create a resource reservation</p>
</div>

<div class="w-full">
    <form method="POST" action="{{ route('admin.reservations.store') }}" class="card">
        @csrf

        <div class="card-body space-y-6">
            <!-- Member Code & Resource Barcode -->
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
                    <label class="form-label">Resource Barcode <span class="text-red-500">*</span></label>
                    <input type="text" name="resource_barcode" class="form-input" required
                           value="{{ old('resource_barcode') }}" placeholder="e.g. 10001">
                    @error('resource_barcode')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
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
            <a href="{{ route('admin.reservations.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Reservation</button>
        </div>
    </form>
</div>
@endsection
