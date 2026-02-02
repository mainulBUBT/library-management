@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Loans</h1>
        <p class="text-gray-500 mt-1">Manage book loans and returns</p>
    </div>
    <a href="{{ route('admin.loans.create') }}" class="btn-primary inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Loan
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.loans.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="search" name="search" placeholder="Search by member or resource..."
                       class="form-input" value="{{ request('search') }}">
            </div>
            <div class="w-full lg:w-40">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.loans.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Loans Table -->
<div class="card">
    <div class="overflow-x-auto">
        <div class="table-wrapper">
        <table class="table">
            <thead class="table-head">
                <tr>
                    <th class="table-th">Resource</th>
                    <th class="table-th">Member</th>
                    <th class="table-th">Borrowed</th>
                    <th class="table-th">Due</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr class="table-tr">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $loan->copy->resource->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $loan->copy->barcode }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="table-td">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $loan->member->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $loan->member->member_code }}</div>
                            </div>
                        </td>
                        <td class="table-td">
                            <span class="text-sm">{{ $loan->borrowed_date->format('M d, Y') }}</span>
                        </td>
                        <td class="table-td">
                            @if($loan->isOverdue() && $loan->status === 'active')
                                <span class="text-sm text-red-600 font-medium">{{ $loan->due_date->format('M d') }}</span>
                                <span class="text-xs text-red-500 block">({{ $loan->daysOverdue() }} days overdue)</span>
                            @else
                                <span class="text-sm">{{ $loan->due_date->format('M d, Y') }}</span>
                            @endif
                        </td>
                        <td class="table-td">
                            @if($loan->status === 'active')
                                @if($loan->isOverdue())
                                    <span class="badge badge-danger">Overdue</span>
                                @else
                                    <span class="badge badge-warning">Active</span>
                                @endif
                            @else
                                <span class="badge badge-success">Returned</span>
                            @endif
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.loans.show', $loan) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($loan->status === 'active')
                                    <form method="POST" action="{{ route('admin.loans.renew', $loan) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-blue-600" title="Renew">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-td text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <p class="font-medium">No loans found</p>
                            <p class="text-sm mt-1">Get started by creating a new loan</p>
                            <a href="{{ route('admin.loans.create') }}" class="btn-primary inline-block mt-3">
                                New Loan
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($loans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $loans->firstItem() }} to {{ $loans->lastItem() }} of {{ $loans->total() }} results
            </div>
            {{ $loans->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
