@extends('layouts.admin')

@section('content')
@php
    $isFiltered = request()->hasAny(['from_date', 'to_date', 'payment_method', 'search']);
@endphp
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
    <p class="text-gray-500 mt-1">Track offline payments and receipts</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Total Collected</p>
                <p class="stat-value mt-1">${{ number_format($totalAmount ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">All-time total</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">{{ $isFiltered ? 'Filtered Total' : 'Recent Total' }}</p>
                <p class="stat-value mt-1">${{ number_format($filteredAmount ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Based on current filters</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Payments</p>
                <p class="stat-value mt-1">{{ $paymentCount ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Matching records</p>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Average Payment</p>
                <p class="stat-value mt-1">${{ number_format($averageAmount ?? 0, 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-2">Filtered average</p>
    </div>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from_date" class="form-input" value="{{ request('from_date') }}">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to_date" class="form-input" value="{{ request('to_date') }}">
            </div>
            <div class="min-w-[180px]">
                <label class="form-label">Method</label>
                <select name="payment_method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="check" {{ request('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                    <option value="money_order" {{ request('payment_method') == 'money_order' ? 'selected' : '' }}>Money Order</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="flex-1 min-w-[220px]">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-input" placeholder="Receipt, member, fine ID..."
                       value="{{ request('search') }}">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary">Filter</button>
                @if($isFiltered)
                    <a href="{{ route('admin.payments.index') }}" class="btn-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Payments</h2>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="table-th">Receipt</th>
                    <th class="table-th">Member</th>
                    <th class="table-th">Fine</th>
                    <th class="table-th">Method</th>
                    <th class="table-th">Amount</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Received By</th>
                    <th class="table-th">Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments ?? [] as $payment)
                <tr class="table-tr">
                    <td class="table-td font-medium">#{{ $payment->receipt_number }}</td>
                    <td class="table-td">
                        <div class="font-medium text-gray-900">{{ $payment->member?->user?->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->member?->member_code ?? 'N/A' }}</div>
                    </td>
                    <td class="table-td">
                        <a href="{{ route('admin.fines.show', $payment->fine_id) }}" class="text-gray-700 hover:text-gray-900">
                            #{{ $payment->fine_id }}
                        </a>
                    </td>
                    <td class="table-td">
                        <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </td>
                    <td class="table-td font-medium">${{ number_format($payment->amount, 2) }}</td>
                    <td class="table-td">{{ $payment->payment_date?->format('M d, Y') ?? '-' }}</td>
                    <td class="table-td">{{ $payment->receivedBy?->user?->name ?? 'System' }}</td>
                    <td class="table-td">
                        <div class="text-xs text-gray-500">
                            {{ $payment->transaction_reference ?? ($payment->check_number ? 'Check ' . $payment->check_number : '—') }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-gray-500 py-8">
                        No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results
            </div>
            {{ $payments->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
