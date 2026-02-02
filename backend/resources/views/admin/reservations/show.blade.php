@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.reservations.index') }}" class="hover:text-gray-700">Reservations</a>
        <span>/</span>
        <span>#{{ $reservation->id }}</span>
    </div>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Reservation #{{ $reservation->id }}</h1>
        @if($reservation->status === 'pending' || $reservation->status === 'ready')
            <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}"
                  onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Cancel Reservation</button>
            </form>
        @endif
    </div>
</div>

<!-- Reservation Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Resource Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Resource</h2>
            </div>
            <div class="card-body">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $reservation->resource->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $reservation->resource->resource_type }}</p>
                        <p class="text-sm text-gray-500 mt-1">ISBN: {{ $reservation->resource->isbn ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Member</h2>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $reservation->member->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $reservation->member->code }}</p>
                        <p class="text-sm text-gray-500">{{ $reservation->member->user->email }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Member Type</p>
                        <p class="font-medium">{{ ucfirst($reservation->member->member_type) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Status</h2>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Current Status</p>
                    @if($reservation->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($reservation->status === 'ready')
                        <span class="badge badge-primary">Ready for Pickup</span>
                    @elseif($reservation->status === 'fulfilled')
                        <span class="badge badge-success">Fulfilled</span>
                    @elseif($reservation->status === 'cancelled')
                        <span class="badge badge-secondary">Cancelled</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Reserved On</p>
                    <p class="font-medium">{{ $reservation->created_at->format('M d, Y - g:i A') }}</p>
                </div>
                @if($reservation->ready_at)
                <div>
                    <p class="text-sm text-gray-500">Ready On</p>
                    <p class="font-medium">{{ $reservation->ready_at->format('M d, Y - g:i A') }}</p>
                </div>
                @endif
                @if($reservation->expires_at)
                <div>
                    <p class="text-sm text-gray-500">Expires On</p>
                    <p class="font-medium">{{ $reservation->expires_at->format('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($reservation->status === 'pending')
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reservations.mark-ready', $reservation) }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full">Mark as Ready</button>
                </form>
            </div>
        </div>
        @elseif($reservation->status === 'ready')
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.reservations.fulfill', $reservation) }}">
                    @csrf
                    <button type="submit" class="btn-success w-full">Fulfill Reservation</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
