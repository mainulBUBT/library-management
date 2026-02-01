<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Copy;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with(['copy.resource', 'member.user', 'staff']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member.user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhereHas('copy.resource', function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                });
            });
        }

        $loans = $query->orderBy('borrowed_date', 'desc')->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.loans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_code' => 'required|string|exists:members,member_code',
            'barcode' => 'required|string|exists:copies,barcode',
            'loan_period_days' => 'nullable|integer|min:1|max:365',
        ]);

        $member = Member::where('member_code', $validated['member_code'])->first();
        $copy = Copy::where('barcode', $validated['barcode'])->first();

        // Check if member is active
        if ($member->status !== 'active') {
            return back()->with('error', 'Member is not active.');
        }

        // Check if member has reached max loans
        $maxLoans = Setting::maxLoans($member->member_type);
        if (!$member->canBorrow($maxLoans)) {
            return back()->with('error', "Member has reached maximum loans ({$maxLoans}).");
        }

        // Check if copy is available
        if (!$copy->isAvailable()) {
            return back()->with('error', 'Copy is not available for borrowing.');
        }

        // Check if member has unpaid fines
        $unpaidFines = $member->unpaidFines()->count();
        if ($unpaidFines > 0) {
            return back()->with('error', 'Member has unpaid fines.');
        }

        // Calculate due date
        $loanPeriod = $validated['loan_period_days'] ?? Setting::loanPeriod($member->member_type);
        $dueDate = now()->addDays($loanPeriod);

        // Create loan
        $loan = Loan::create([
            'copy_id' => $copy->id,
            'member_id' => $member->id,
            'staff_id' => auth()->user()->staff?->id,
            'borrowed_date' => now(),
            'due_date' => $dueDate,
            'status' => 'active',
        ]);

        // Update copy status
        $copy->update(['status' => 'borrowed']);

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['copy.resource', 'copy.resource.authors', 'member.user', 'staff.user', 'fines']);

        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        //
    }

    /**
     * Return a loan.
     */
    public function return(Request $request, Loan $loan)
    {
        if ($loan->status === 'returned') {
            return back()->with('error', 'Loan has already been returned.');
        }

        $validated = $request->validate([
            'condition' => 'required|in:new,good,fair,poor,damaged',
            'notes' => 'nullable|string',
        ]);

        // Update loan
        $loan->update([
            'return_date' => now(),
            'status' => 'returned',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update copy status and condition
        $loan->copy->update([
            'status' => 'available',
            'condition' => $validated['condition'],
        ]);

        // Calculate fine if overdue
        if ($loan->isOverdue()) {
            $daysOverdue = $loan->daysOverdue();
            $fineAmount = $daysOverdue * Setting::finePerDay();

            \App\Models\Fine::create([
                'member_id' => $loan->member_id,
                'loan_id' => $loan->id,
                'copy_id' => $loan->copy_id,
                'fine_type' => 'late_return',
                'amount' => $fineAmount,
                'status' => 'pending',
                'calculated_at' => now(),
                'description' => "{$daysOverdue} days overdue",
            ]);
        }

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan returned successfully.');
    }

    /**
     * Renew a loan.
     */
    public function renew(Loan $loan)
    {
        if (!$loan->canRenew(Setting::maxRenewals())) {
            return back()->with('error', 'Loan cannot be renewed.');
        }

        $loanPeriod = Setting::loanPeriod($loan->member->member_type);
        $newDueDate = $loan->calculateRenewalDate($loanPeriod);

        $loan->update([
            'due_date' => $newDueDate,
            'renewed_count' => $loan->renewed_count + 1,
        ]);

        return back()->with('success', 'Loan renewed successfully.');
    }
}
