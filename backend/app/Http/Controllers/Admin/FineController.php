<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Fine::with(['member.user', 'loan.copy.resource', 'copy']);

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
                });
            });
        }

        $fines = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.fines.index', compact('fines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = \App\Models\Member::where('status', 'active')
            ->with('user')
            ->get();

        return view('admin.fines.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'fine_type' => 'required|in:late_return,damage,lost,other',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'copy_id' => 'nullable|exists:copies,id',
            'loan_id' => 'nullable|exists:loans,id',
        ]);

        Fine::create([
            'member_id' => $validated['member_id'],
            'loan_id' => $validated['loan_id'] ?? null,
            'copy_id' => $validated['copy_id'] ?? null,
            'fine_type' => $validated['fine_type'],
            'amount' => $validated['amount'],
            'status' => 'pending',
            'calculated_at' => now(),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.fines.index')
            ->with('success', 'Fine created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        $fine->load(['member.user', 'loan.copy.resource', 'copy', 'payments.receivedBy.user']);

        return view('admin.fines.show', compact('fine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fine $fine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fine $fine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fine $fine)
    {
        //
    }

    /**
     * Waive a fine.
     */
    public function waive(Request $request, Fine $fine)
    {
        if ($fine->status === 'paid') {
            return back()->with('error', 'Cannot waive a paid fine.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $fine->waive(auth()->user()->staff?->id, $validated['reason']);

        return back()->with('success', 'Fine waived successfully.');
    }
}
