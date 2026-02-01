@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.members.index') }}" class="hover:text-gray-700">Members</a>
        <span>/</span>
        <span>{{ $member->member_code }}</span>
    </div>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $member->user->name }}</h1>
            <p class="text-gray-500 mt-1">{{ $member->member_code }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.members.edit', $member) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Member Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Member Information</h2>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->user->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Type</dt>
                        <dd class="mt-1">
                            <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $member->member_type)) }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($member->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($member->status === 'suspended')
                                <span class="badge badge-danger">Suspended</span>
                            @else
                                <span class="badge badge-warning">Expired</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->joined_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->expiry_date?->format('M d, Y') ?? 'Never' }}</dd>
                    </div>
                </dl>
                @if($member->user->address)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $member->user->address }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Loans -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent Loans</h2>
                <a href="{{ route('admin.loans.index', ['member' => $member->id]) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View all</a>
            </div>
            <div class="card-body p-0">
                @if($member->loans->count() > 0)
                    <table class="table">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th">Resource</th>
                                <th class="table-th">Borrowed</th>
                                <th class="table-th">Due</th>
                                <th class="table-th">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($member->loans as $loan)
                                <tr class="table-tr">
                                    <td class="table-td">{{ $loan->copy->resource->title }}</td>
                                    <td class="table-td">{{ $loan->borrowed_date->format('M d') }}</td>
                                    <td class="table-td">
                                        @if($loan->due_date->isPast())
                                            <span class="text-red-600">{{ $loan->due_date->format('M d') }}</span>
                                        @else
                                            <span>{{ $loan->due_date->format('M d') }}</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($loan->status === 'active')
                                            <span class="badge badge-warning">Active</span>
                                        @elseif($loan->status === 'returned')
                                            <span class="badge badge-success">Returned</span>
                                        @elseif($loan->status === 'overdue')
                                            <span class="badge badge-danger">Overdue</span>
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

        <!-- Fines -->
        @if($member->fines->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Outstanding Fines</h2>
                    <a href="{{ route('admin.fines.index', ['member' => $member->id]) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">View all</a>
                </div>
                <div class="card-body">
                    @foreach($member->fines as $fine)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $fine->reason }}</p>
                                <p class="text-xs text-gray-500">{{ $fine->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">${{ number_format($fine->amount - $fine->paid_amount, 2) }}</p>
                                <span class="text-xs {{ $fine->status === 'pending' ? 'text-red-600' : 'text-yellow-600' }}">
                                    {{ ucfirst($fine->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Stats -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Statistics</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Active Loans</span>
                    <span class="text-lg font-semibold">{{ $activeLoans }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Unpaid Fines</span>
                    <span class="text-lg font-semibold @if($unpaidFines > 0) text-red-600 @endif">
                        ${{ number_format($unpaidFines, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('admin.loans.create', ['member' => $member->id]) }}"
                   class="btn-primary w-full text-center">
                    Checkout Item
                </a>
                <a href="{{ route('admin.reservations.create', ['member' => $member->id]) }}"
                   class="btn-secondary w-full text-center">
                    Create Reservation
                </a>
                @if($unpaidFines > 0)
                    <a href="{{ route('admin.fines.create', ['member' => $member->id]) }}"
                       class="btn-danger w-full text-center">
                        Record Payment
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
