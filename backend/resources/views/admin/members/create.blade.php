@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.members.index') }}" class="hover:text-gray-700">Members</a>
        <span>/</span>
        <span>Add New</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Add Member</h1>
    <p class="text-gray-500 mt-1">Register a new library member</p>
</div>

<div class="w-full">
    <div class="mx-auto w-full max-w-4xl">
    <form method="POST" action="{{ route('admin.members.store') }}" class="card">
        @csrf

        <div class="card-body space-y-6">
            <!-- Name & Email -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-input" required
                           value="{{ old('name') }}" autofocus>
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" class="form-input" required
                           value="{{ old('email') }}">
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
                           value="{{ old('phone') }}" placeholder="+1234567890">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Member Type <span class="text-red-500">*</span></label>
                    <select name="member_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="student" {{ old('member_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('member_type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="staff" {{ old('member_type') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="public" {{ old('member_type') == 'public' ? 'selected' : '' }}>Public</option>
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
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-input"
                           value="{{ old('expiry_date') }}" min="{{ now()->addDay()->format('Y-m-d') }}">
                    @error('expiry_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="form-label">Address</label>
                <textarea name="address" rows="3" class="form-textarea"
                          placeholder="Full address...">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Credentials</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.members.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Member</button>
        </div>
    </form>
    </div>
</div>
@endsection
