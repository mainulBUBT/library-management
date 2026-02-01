@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Members</h1>
        <p class="text-gray-500 mt-1">Manage library members</p>
    </div>
    <a href="{{ route('admin.members.create') }}" class="btn-primary inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Member
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.members.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="search" name="search" placeholder="Search by name, email, or code..."
                       class="form-input" value="{{ request('search') }}">
            </div>
            <div class="w-full lg:w-40">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="w-full lg:w-40">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ request('type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Public</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'status', 'type']))
                <a href="{{ route('admin.members.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Members Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="table-head">
                <tr>
                    <th class="table-th">Member</th>
                    <th class="table-th">Code</th>
                    <th class="table-th">Type</th>
                    <th class="table-th">Status</th>
                    <th class="table-th">Joined</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="table-tr">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                    <span class="text-primary-600 font-semibold">{{ strtoupper(substr($member->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $member->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-td">
                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $member->member_code }}</code>
                        </td>
                        <td class="table-td">
                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $member->member_type)) }}</span>
                        </td>
                        <td class="table-td">
                            @if($member->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($member->status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">Expired</span>
                            @endif
                        </td>
                        <td class="table-td">
                            <span class="text-sm">{{ $member->joined_date->format('M d, Y') }}</span>
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.members.show', $member) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.members.destroy', $member) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this member?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-td text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="font-medium">No members found</p>
                            <p class="text-sm mt-1">Get started by adding your first member</p>
                            <a href="{{ route('admin.members.create') }}" class="btn-primary inline-block mt-3">
                                Add Member
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} results
            </div>
            {{ $members->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
