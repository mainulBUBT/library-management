@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.loans.index') }}" class="hover:text-gray-700">Loans</a>
        <span>/</span>
        <span>Loan #{{ $loan->id }}</span>
    </div>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Loan Details</h1>
            <p class="text-gray-500 mt-1">ID: #{{ $loan->id }}</p>
        </div>
        @if($loan->status === 'active')
            <form method="POST" action="{{ route('admin.loans.renew', $loan) }}" class="inline">
                @csrf
                <button type="submit" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Renew
                </button>
            </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Loan Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Loan Information</h2>
                @if($loan->status === 'active')
                    <span class="badge {{ $loan->isOverdue() ? 'badge-danger' : 'badge-warning' }}">
                        {{ $loan->isOverdue() ? 'Overdue' : 'Active' }}
                    </span>
                @else
                    <span class="badge badge-success">Returned</span>
                @endif
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Borrowed Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $loan->borrowed_date->format('M d, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                        <dd class="mt-1 text-sm @if($loan->isOverdue()) text-red-600 @endif">
                            {{ $loan->due_date->format('M d, Y') }}
                            @if($loan->status === 'active')
                                @if($loan->isOverdue())
                                    <span class="text-red-500 ml-2">({{ $loan->daysOverdue() }} days overdue)</span>
                                @else
                                    <span class="text-gray-500 ml-2">({{ $loan->due_date->diffInDays(now()) }} days left)</span>
                                @endif
                            @endif
                        </dd>
                    </div>
                    @if($loan->return_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Returned Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $loan->return_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Renewals</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $loan->renewed_count }} / 2</dd>
                    </div>
                    @if($loan->staff)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Processed By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $loan->staff->user->name }}</dd>
                        </div>
                    @endif
                </dl>
                @if($loan->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $loan->notes }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Resource Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Resource</h2>
            </div>
            <div class="card-body">
                <div class="flex items-start">
                    <div class="w-16 h-20 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $loan->copy->resource->title }}</h3>
                        @if($loan->copy->resource->authors->count() > 0)
                            <p class="text-sm text-gray-500 mt-1">{{ $loan->copy->resource->authors->pluck('name')->implode(', ') }}</p>
                        @endif>
                        <p class="text-sm text-gray-500 mt-1">
                            Barcode: <code class="bg-gray-100 px-1 rounded">{{ $loan->copy->barcode }}</code>
                        </p>
                        <p class="text-sm text-gray-500">
                            Copy: {{ $loan->copy->copy_number }} | Condition: {{ ucfirst($loan->copy->condition) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Return Form (if active) -->
        @if($loan->status === 'active')
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Return Item</h2>
                </div>
                <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="card-body">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Condition <span class="text-red-500">*</span></label>
                            <select name="condition" class="form-select" required>
                                <option value="new">New</option>
                                <option value="good" selected>Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="damaged">Damaged</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="2" class="form-textarea" placeholder="Any notes about the return..."></textarea>
                        </div>
                        @if($loan->isOverdue())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    This item is overdue by {{ $loan->daysOverdue() }} days.
                                    A fine of ${{ number_format($loan->daysOverdue() * 0.5, 2) }} will be calculated.
                                </p>
                            </div>
                        @endif
                        <button type="submit" class="btn-success w-full">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Process Return
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Member Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Member</h2>
            </div>
            <div class="card-body">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                        <span class="text-primary-600 font-semibold">{{ strtoupper(substr($loan->member->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $loan->member->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $loan->member->member_code }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-900">{{ $loan->member->user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Type</span>
                        <span class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $loan->member->member_type)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="badge {{ $loan->member->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                            {{ ucfirst($loan->member->status) }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.members.show', $loan->member) }}" class="block mt-4 text-center text-sm text-primary-600 hover:text-primary-700">
                    View Member Profile
                </a>
            </div>
        </div>

        <!-- Related Fines -->
        @if($loan->fines->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Related Fines</h2>
                </div>
                <div class="card-body">
                    @foreach($loan->fines as $fine)
                        <div class="mb-3 last:mb-0">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $fine->fine_type)) }}</span>
                                <span class="font-semibold @if($fine->status === 'pending') text-red-600 @endif">
                                    ${{ number_format($fine->amount, 2) }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $fine->description }}</p>
                            <span class="text-xs @if($fine->status === 'pending') text-red-500 @elseif($fine->status === 'paid') text-green-500 @else text-yellow-500 @endif">
                                {{ ucfirst($fine->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
