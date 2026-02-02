@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Reservations</h1>
    <p class="text-gray-500 mt-1">Manage resource reservations</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Reservations -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-500 truncate">Total Reservations</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalReservations ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="text-xs font-medium text-gray-500">All reservations</span>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-500 truncate">Pending</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pendingCount ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                {{ (($totalReservations ?? 0) > 0 && ($pendingCount ?? 0) > 0) ? round((($pendingCount ?? 0) / ($totalReservations ?? 1)) * 100, 1) : 0 }}%
            </span>
            <span class="text-xs font-medium text-gray-500">Awaiting processing</span>
        </div>
    </div>

    <!-- Ready -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-500 truncate">Ready</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $readyCount ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                {{ (($totalReservations ?? 0) > 0 && ($readyCount ?? 0) > 0) ? round((($readyCount ?? 0) / ($totalReservations ?? 1)) * 100, 1) : 0 }}%
            </span>
            <span class="text-xs font-medium text-gray-500">Ready for pickup</span>
        </div>
    </div>

    <!-- Fulfilled -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-500 truncate">Fulfilled</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $fulfilledCount ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                {{ (($totalReservations ?? 0) > 0 && ($fulfilledCount ?? 0) > 0) ? round((($fulfilledCount ?? 0) / ($totalReservations ?? 1)) * 100, 1) : 0 }}%
            </span>
            <span class="text-xs font-medium text-gray-500">Completed</span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" class="form-input" placeholder="Search by member or resource..."
                       value="{{ request('search') }}">
            </div>
            <div class="flex items-center gap-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-primary">Search</button>
                <a href="{{ route('admin.reservations.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Reservations Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Reservations</h2>
        <a href="{{ route('admin.reservations.create') }}" class="btn-primary btn-sm">New Reservation</a>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-th">ID</th>
                    <th class="table-th">Member</th>
                    <th class="table-th">Resource</th>
                    <th class="table-th">Status</th>
                    <th class="table-th">Reserved Date</th>
                    <th class="table-th">Ready Date</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations ?? [] as $reservation)
                <tr class="table-tr">
                    <td class="table-td font-medium">#{{ $reservation->id }}</td>
                    <td class="table-td">
                        <div class="font-medium text-gray-900">{{ $reservation->member?->user?->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $reservation->member?->code ?? 'N/A' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-gray-900">{{ $reservation->resource?->title ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $reservation->resource?->resource_type ?? 'N/A' }}</div>
                    </td>
                    <td class="table-td">
                        @if($reservation->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($reservation->status === 'ready')
                            <span class="badge badge-primary">Ready</span>
                        @elseif($reservation->status === 'fulfilled')
                            <span class="badge badge-success">Fulfilled</span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge badge-secondary">Cancelled</span>
                        @endif
                    </td>
                    <td class="table-td">{{ $reservation->created_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-td">{{ $reservation->ready_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-td text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($reservation->status === 'pending')
                                <form method="POST" action="{{ route('admin.reservations.mark-ready', $reservation) }}">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-primary">Mark Ready</button>
                                </form>
                            @elseif($reservation->status === 'ready')
                                <form method="POST" action="{{ route('admin.reservations.fulfill', $reservation) }}">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-success">Fulfill</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.reservations.show', $reservation) }}"
                               class="text-gray-500 hover:text-gray-700">View</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="table-td text-center text-gray-500 py-8">
                        No reservations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
