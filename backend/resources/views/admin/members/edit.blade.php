@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.members.index') }}" class="hover:text-gray-700">Members</a>
        <span>/</span>
        <span>Edit</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Edit Member</h1>
    <p class="text-gray-500 mt-1">Update member information</p>
</div>

<div class="w-full">
    <form method="POST" action="{{ route('admin.members.update', $member) }}" class="card">
        @csrf
        @method('PUT')

        <div class="card-body space-y-6">
            <!-- Name & Email -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required
                           value="{{ old('name', $member->user->name) }}" autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="form-input" required
                           value="{{ old('email', $member->user->email) }}">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Phone & Member Type -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input"
                           value="{{ old('phone', $member->user->phone) }}" placeholder="+1234567890">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Member Type <span class="text-red-500">*</span></label>
                    <select name="member_type" class="form-select select2" required>
                        <option value="">Select type...</option>
                        <option value="student" {{ old('member_type', $member->member_type) == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('member_type', $member->member_type) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="staff" {{ old('member_type', $member->member_type) == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="public" {{ old('member_type', $member->member_type) == 'public' ? 'selected' : '' }}>Public</option>
                    </select>
                    @error('member_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status & Expiry -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-select select2" required>
                        <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status', $member->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="expired" {{ old('status', $member->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input"
                           value="{{ old('expiry_date', $member->expiry_date?->format('Y-m-d')) }}">
                    @error('expiry_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="form-label">Address</label>
                <textarea name="address" rows="3" class="form-textarea"
                          placeholder="Full address...">{{ old('address', $member->user->address) }}</textarea>
                @error('address')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between rounded-b-xl">
            <a href="{{ route('admin.members.show', $member) }}" class="text-gray-500 hover:text-gray-700">View member</a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.members.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Member</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<style>
.select2-container .select2-selection {
    border-color: #d1d5db;
    min-height: 38px;
}
.select2-container--default .select2-selection--single {
    display: flex;
    align-items: center;
}
.select2-dropdown {
    border-color: #d1d5db;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('select:not([multiple])').select2({
        theme: 'default',
        width: '100%'
    });
});
</script>
@endpush
@endsection
