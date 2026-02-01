@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Total Resources</p>
                <p class="stat-value mt-1">{{ $stats['total_resources'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">{{ $stats['total_copies'] }} total copies</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Active Members</p>
                <p class="stat-value mt-1">{{ $stats['total_members'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656.126-1.283.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Registered members</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Active Loans</p>
                <p class="stat-value mt-1">{{ $stats['active_loans'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
        @if($stats['overdue_loans'] > 0)
            <p class="text-sm text-red-600 mt-2">{{ $stats['overdue_loans'] }} overdue</p>
        @else
            <p class="text-sm text-gray-500 mt-2">No overdue items</p>
        @endif
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Pending Fines</p>
                <p class="stat-value mt-1">${{ number_format($stats['pending_fines'], 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Unpaid fines</p>
    </div>
</div>

<!-- Overdue Loans Warning -->
@if($stats['overdue_loans'] > 0)
<div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
    <div class="flex items-center">
        <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <h3 class="text-red-800 font-semibold">{{ $stats['overdue_loans'] }} Overdue Loans</h3>
            <p class="text-red-600 text-sm">Items need to be returned. <a href="{{ route('admin.loans.index', ['status' => 'overdue']) }}" class="underline font-medium">View all</a></p>
        </div>
    </div>
</div>
@endif

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Loans -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Loans</h2>
            <a href="{{ route('admin.loans.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View all</a>
        </div>
        <div class="card-body p-0">
            @if($recentLoans->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table-th">Resource</th>
                            <th class="table-th">Member</th>
                            <th class="table-th">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                        <tr class="table-tr">
                            <td class="table-td">
                                <div class="font-medium text-gray-900">{{ $loan->copy->resource->title }}</div>
                                <div class="text-xs text-gray-500">{{ $loan->copy->barcode }}</div>
                            </td>
                            <td class="table-td">{{ $loan->member->user->name }}</td>
                            <td class="table-td">
                                @if($loan->due_date->isPast())
                                    <span class="badge badge-danger">{{ $loan->due_date->format('M d') }}</span>
                                @else
                                    <span class="badge badge-success">{{ $loan->due_date->format('M d') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500">No loans yet</div>
            @endif
        </div>
    </div>

    <!-- Recent Members -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Members</h2>
            <a href="{{ route('admin.members.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View all</a>
        </div>
        <div class="card-body p-0">
            @if($recentMembers->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table-th">Member</th>
                            <th class="table-th">Code</th>
                            <th class="table-th">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentMembers as $member)
                        <tr class="table-tr">
                            <td class="table-td">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                        <span class="text-primary-600 font-semibold text-xs">{{ strtoupper(substr($member->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $member->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $member->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-td"><code class="text-xs">{{ $member->member_code }}</code></td>
                            <td class="table-td">
                                <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $member->member_type)) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500">No members yet</div>
            @endif
        </div>
    </div>
</div>

<!-- Pending Reservations -->
@if($stats['pending_reservations'] > 0)
<div class="card mb-8">
    <div class="card-header">
        <h2 class="card-title">Pending Reservations</h2>
        <a href="{{ route('admin.reservations.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View all</a>
    </div>
    <div class="card-body">
        <div class="flex items-center text-gray-600">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
            <span>{{ $stats['pending_reservations'] }} reservations waiting to be processed</span>
        </div>
    </div>
</div>
@endif
@endsection
