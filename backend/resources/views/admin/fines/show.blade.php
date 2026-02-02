@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.fines.index') }}" class="hover:text-gray-700">Fines</a>
        <span>/</span>
        <span>#{{ $fine->id }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Fine #{{ $fine->id }}</h1>
</div>

<!-- Fine Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Loan Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Related Loan</h2>
            </div>
            <div class="card-body">
                @if($fine->loan)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Loan ID</p>
                            <p class="font-medium text-gray-900">#{{ $fine->loan->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Resource</p>
                            <p class="font-medium">{{ $fine->loan?->copy?->resource?->title ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Due Date</p>
                            <p class="font-medium">{{ $fine->loan?->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Returned Date</p>
                            <p class="font-medium">{{ $fine->loan?->returned_at?->format('M d, Y') ?? 'Not returned' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No loan linked to this fine.</p>
                @endif
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
                        <p class="font-medium text-gray-900">{{ $fine->member?->user?->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $fine->member?->member_code ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $fine->member?->user?->email ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Member Type</p>
                        <p class="font-medium">{{ ucfirst($fine->member?->member_type ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Payments</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table-th">Receipt</th>
                            <th class="table-th">Method</th>
                            <th class="table-th">Amount</th>
                            <th class="table-th">Date</th>
                            <th class="table-th">Received By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fine->payments ?? [] as $payment)
                            <tr class="table-tr">
                                <td class="table-td font-medium">#{{ $payment->receipt_number }}</td>
                                <td class="table-td">
                                    <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                </td>
                                <td class="table-td font-medium">${{ number_format($payment->amount, 2) }}</td>
                                <td class="table-td">{{ $payment->payment_date?->format('M d, Y - g:i A') ?? '-' }}</td>
                                <td class="table-td">{{ $payment->receivedBy?->user?->name ?? 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-td text-center text-gray-500 py-6">
                                    No payments recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Fine Amount Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Fine Details</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($fine->amount, 2) }}</p>
                    </div>
                    @if($fine->status === 'pending')
                        <span class="badge badge-danger">Unpaid</span>
                    @elseif($fine->status === 'partially_paid')
                        <span class="badge badge-warning">Partially Paid</span>
                    @elseif($fine->status === 'paid')
                        <span class="badge badge-success">Paid</span>
                    @elseif($fine->status === 'waived')
                        <span class="badge badge-secondary">Waived</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500">Paid Amount</p>
                    <p class="font-medium">${{ number_format($fine->paid_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Balance</p>
                    <p class="font-medium">${{ number_format($fine->balance, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Type</p>
                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $fine->fine_type)) }}</p>
                </div>
                @if($fine->description)
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="font-medium">{{ $fine->description }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Date Issued</p>
                    <p class="font-medium">{{ $fine->created_at->format('M d, Y') }}</p>
                </div>
                @if($fine->due_date)
                    <div>
                        <p class="text-sm text-gray-500">Due Date</p>
                        <p class="font-medium">{{ $fine->due_date->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($fine->waived_at)
                    <div>
                        <p class="text-sm text-gray-500">Waived On</p>
                        <p class="font-medium">{{ $fine->waived_at->format('M d, Y - g:i A') }}</p>
                    </div>
                @endif
                @if($fine->waiver_reason)
                    <div>
                        <p class="text-sm text-gray-500">Waiver Reason</p>
                        <p class="font-medium">{{ $fine->waiver_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Record Payment -->
        @if($fine->status !== 'waived' && $fine->balance > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Record Payment</h2>
            </div>
            <div class="card-body space-y-4">
                <form method="POST" action="{{ route('admin.fines.payment', $fine) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Amount ($)</label>
                        <input type="number" name="amount" class="form-input" min="0.01" step="0.01" max="{{ $fine->balance }}"
                               value="{{ old('amount', $fine->balance) }}" required>
                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="money_order" {{ old('payment_method') == 'money_order' ? 'selected' : '' }}>Money Order</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Check Number (if applicable)</label>
                        <input type="text" name="check_number" class="form-input" value="{{ old('check_number') }}">
                        @error('check_number')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Transaction Reference</label>
                        <input type="text" name="transaction_reference" class="form-input" value="{{ old('transaction_reference') }}">
                        @error('transaction_reference')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3" class="form-textarea">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full">Record Payment</button>
                </form>
            </div>
        </div>
        @endif

        <!-- Actions -->
        @if($fine->status !== 'paid' && $fine->status !== 'waived')
        <div class="card">
            <div class="card-body space-y-3">
                <form method="POST" action="{{ route('admin.fines.waive', $fine) }}"
                      onsubmit="return confirm('Are you sure you want to waive this fine?');">
                    @csrf
                    <div>
                        <label class="form-label">Waiver Reason</label>
                        <textarea name="reason" rows="3" class="form-textarea" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-secondary w-full">Waive Fine</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
