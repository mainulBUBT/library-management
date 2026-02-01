<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['resource', 'member.user', 'copy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('reserved_at', 'desc')->paginate(15);

        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $resources = Resource::where('status', 'available')->get(['id', 'title']);
        $members = \App\Models\Member::where('status', 'active')
            ->with('user')
            ->get();

        return view('admin.reservations.create', compact('resources', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'member_id' => 'required|exists:members,id',
        ]);

        // Check if resource exists
        $resource = Resource::find($validated['resource_id']);

        Reservation::create([
            'resource_id' => $validated['resource_id'],
            'member_id' => $validated['member_id'],
            'status' => 'pending',
            'reserved_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['resource', 'member.user', 'copy']);

        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation cancelled successfully.');
    }

    /**
     * Mark reservation as ready.
     */
    public function markReady(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservation cannot be marked as ready.');
        }

        // Find an available copy
        $availableCopy = $reservation->resource->availableCopies()->first();

        if (!$availableCopy) {
            return back()->with('error', 'No available copies for this resource.');
        }

        $reservation->update([
            'status' => 'ready',
            'copy_id' => $availableCopy->id,
            'ready_at' => now(),
            'expires_at' => now()->addDays(3),
        ]);

        return back()->with('success', 'Reservation marked as ready.');
    }

    /**
     * Fulfill reservation (convert to loan).
     */
    public function fulfill(Reservation $reservation)
    {
        if ($reservation->status !== 'ready') {
            return back()->with('error', 'Reservation must be ready before fulfillment.');
        }

        $member = $reservation->member;
        $copy = $reservation->copy;

        // Check if member can borrow
        $maxLoans = \App\Models\Setting::maxLoans($member->member_type);
        if (!$member->canBorrow($maxLoans)) {
            return back()->with('error', "Member has reached maximum loans ({$maxLoans}).");
        }

        // Calculate due date
        $loanPeriod = \App\Models\Setting::loanPeriod($member->member_type);
        $dueDate = now()->addDays($loanPeriod);

        // Create loan
        \App\Models\Loan::create([
            'copy_id' => $copy->id,
            'member_id' => $member->id,
            'staff_id' => auth()->user()->staff?->id,
            'borrowed_date' => now(),
            'due_date' => $dueDate,
            'status' => 'active',
        ]);

        // Update copy status
        $copy->update(['status' => 'borrowed']);

        // Update reservation
        $reservation->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        return redirect()->route('admin.loans.show', \App\Models\Loan::latest()->first())
            ->with('success', 'Reservation fulfilled successfully.');
    }
}
