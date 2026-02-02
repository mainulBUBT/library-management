@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Fines</h1>
    <p class="text-gray-500 mt-1">Manage overdue fines and payments</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Total Fines -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Total Fines</p>
                <p class="stat-value mt-1">${{ number_format($totalFines ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">All time fines</p>
    </div>

    <!-- Paid -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Paid</p>
                <p class="stat-value mt-1">${{ number_format($paidFines ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">
            {{ (($totalFines ?? 0) > 0 && ($paidFines ?? 0) > 0) ? round((($paidFines ?? 0) / ($totalFines ?? 1)) * 100, 1) : 0 }}% collected
        </p>
    </div>

    <!-- Outstanding -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Outstanding</p>
                <p class="stat-value mt-1">${{ number_format($outstandingFines ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">
            {{ (($totalFines ?? 0) > 0 && ($outstandingFines ?? 0) > 0) ? round((($outstandingFines ?? 0) / ($totalFines ?? 1)) * 100, 1) : 0 }}% unpaid
        </p>
    </div>

    <!-- Active Count -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Active Fines</p>
                <p class="stat-value mt-1">{{ $activeCount ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Pending count</p>
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.fines.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" class="form-input" placeholder="Search by member or loan ID..."
                       value="{{ request('search') }}">
            </div>
            <div class="flex items-center gap-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="waived" {{ request('status') == 'waived' ? 'selected' : '' }}>Waived</option>
                </select>
                <button type="submit" class="btn-primary">Search</button>
                <a href="{{ route('admin.fines.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Fines Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Fines</h2>
        <a href="{{ route('admin.fines.create') }}" class="btn-primary btn-sm">Add Manual Fine</a>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-th">ID</th>
                    <th class="table-th">Member</th>
                    <th class="table-th">Loan</th>
                    <th class="table-th">Amount</th>
                    <th class="table-th">Status</th>
                    <th class="table-th">Date Issued</th>
                    <th class="table-th">Date Paid</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fines ?? [] as $fine)
                <tr class="table-tr">
                    <td class="table-td font-medium">#{{ $fine->id }}</td>
                    <td class="table-td">
                        <div class="font-medium text-gray-900">{{ $fine->loan?->member?->user?->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $fine->loan?->member?->code ?? 'N/A' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="text-sm">#{{ $fine->loan?->id ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $fine->loan?->copy?->resource?->title ?? 'N/A' }}</div>
                    </td>
                    <td class="table-td font-medium">${{ number_format($fine->amount, 2) }}</td>
                    <td class="table-td">
                        @if($fine->status === 'pending')
                            <span class="badge badge-danger">Pending</span>
                        @elseif($fine->status === 'partially_paid')
                            <span class="badge badge-warning">Partially Paid</span>
                        @elseif($fine->status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @elseif($fine->status === 'waived')
                            <span class="badge badge-secondary">Waived</span>
                        @endif
                    </td>
                    <td class="table-td">{{ $fine->created_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-td">{{ $fine->paid_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-td text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($fine->status === 'pending')
                                <form method="POST" action="{{ route('admin.fines.waive', $fine) }}"
                                      onsubmit="return confirm('Are you sure you want to waive this fine?');">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-secondary">Waive</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.fines.show', $fine) }}"
                               class="text-gray-500 hover:text-gray-700">View</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-gray-500 py-8">
                        No fines found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
