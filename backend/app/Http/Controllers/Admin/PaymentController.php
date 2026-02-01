<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Payment::with(['fine', 'member.user', 'receivedBy.user']);

        // Filter by date
        if ($request->filled('from_date')) {
            $query->where('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('payment_date', '<=', $request->to_date);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        $totalAmount = \App\Models\Payment::sum('amount');

        return view('admin.payments.index', compact('payments', 'totalAmount'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $fine->balance,
            'payment_method' => 'required|in:cash,check,money_order,bank_transfer,other',
            'check_number' => 'required_if:payment_method,check|nullable|string|max:50',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $remainingBalance = $fine->balance - $validated['amount'];
        $newStatus = $remainingBalance <= 0 ? 'paid' : 'partially_paid';

        // Generate receipt number
        $receiptNumber = 'RCP' . date('Ymd') . str_pad(\App\Models\Payment::count() + 1, 5, '0', STR_PAD_LEFT);

        // Create payment
        \App\Models\Payment::create([
            'fine_id' => $fine->id,
            'member_id' => $fine->member_id,
            'received_by' => auth()->user()->staff?->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'check_number' => $validated['check_number'] ?? null,
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'payment_date' => now(),
            'notes' => $validated['notes'] ?? null,
            'receipt_number' => $receiptNumber,
        ]);

        // Update fine
        $fine->update([
            'paid_amount' => $fine->paid_amount + $validated['amount'],
            'status' => $newStatus,
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }
}
