<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Copy;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_resources' => Resource::count(),
            'total_copies' => Copy::count(),
            'available_copies' => Copy::where('status', 'available')->count(),
            'total_members' => Member::where('status', 'active')->count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'overdue_loans' => Loan::where('status', 'active')->where('due_date', '<', now())->count(),
            'pending_fines' => Fine::whereIn('status', ['pending', 'partially_paid'])->sum('amount') - Fine::whereIn('status', ['pending', 'partially_paid'])->sum('paid_amount'),
            'pending_reservations' => \App\Models\Reservation::where('status', 'pending')->count(),
        ];

        // Recent active loans
        $recentLoans = Loan::with(['copy.resource', 'member.user'])
            ->where('status', 'active')
            ->orderBy('borrowed_date', 'desc')
            ->limit(10)
            ->get();

        // Overdue loans
        $overdueLoans = Loan::with(['copy.resource', 'member.user'])
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        // Recent members
        $recentMembers = Member::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Loans chart data (last 30 days)
        $loansChart = Loan::select(DB::raw('DATE(borrowed_date) as date'), DB::raw('COUNT(*) as count'))
            ->where('borrowed_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentLoans',
            'overdueLoans',
            'recentMembers',
            'loansChart'
        ));
    }
}
