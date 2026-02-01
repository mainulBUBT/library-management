<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('member_type', $request->type);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'member_type' => 'required|in:student,teacher,staff,public',
            'status' => 'required|in:active,suspended,expired',
            'expiry_date' => 'nullable|date|after:today',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'member',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Generate member code
        $memberCode = 'MEM' . date('Y') . str_pad(Member::count() + 1, 5, '0', STR_PAD_LEFT);

        // Create member
        $member = Member::create([
            'user_id' => $user->id,
            'member_code' => $memberCode,
            'member_type' => $validated['member_type'],
            'status' => $validated['status'],
            'joined_date' => now(),
            'expiry_date' => $validated['expiry_date'] ?? null,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $member->load(['user', 'loans' => function ($query) {
            $query->with('copy.resource')->orderBy('borrowed_date', 'desc')->limit(10);
        }, 'fines' => function ($query) {
            $query->whereIn('status', ['pending', 'partially_paid']);
        }]);

        $activeLoans = $member->activeLoans()->count();
        $unpaidFines = $member->unpaidFines()->sum('amount') - $member->unpaidFines()->sum('paid_amount');

        return view('admin.members.show', compact('member', 'activeLoans', 'unpaidFines'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $member->load('user');

        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'member_type' => 'required|in:student,teacher,staff,public',
            'status' => 'required|in:active,suspended,expired',
            'expiry_date' => 'nullable|date',
        ]);

        // Update user
        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Update member
        $member->update([
            'member_type' => $validated['member_type'],
            'status' => $validated['status'],
            'expiry_date' => $validated['expiry_date'] ?? null,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // Check if member has active loans
        if ($member->activeLoans()->count() > 0) {
            return back()->with('error', 'Cannot delete member with active loans.');
        }

        // Check if member has unpaid fines
        if ($member->unpaidFines()->count() > 0) {
            return back()->with('error', 'Cannot delete member with unpaid fines.');
        }

        $member->user()->delete();
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
