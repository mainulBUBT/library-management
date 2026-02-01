<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\LoanResource;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    /**
     * Get authenticated user's loans.
     */
    public function myLoans(Request $request)
    {
        $user = $request->user();

        $loans = Loan::with(['copy.resource', 'copy.resource.category', 'copy.resource.author'])
            ->where('member_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return LoanResource::collection($loans);
    }

    /**
     * Renew a loan.
     */
    public function renew(Request $request, $id)
    {
        $user = $request->user();

        $loan = Loan::with('copy')
            ->where('member_id', $user->id)
            ->findOrFail($id);

        // Check if can be renewed
        if ($loan->status !== 'active') {
            return new JsonResponse([
                'message' => 'This loan cannot be renewed.',
            ], 400);
        }

        if ($loan->renewal_count >= 3) {
            return new JsonResponse([
                'message' => 'Maximum renewal limit reached.',
            ], 400);
        }

        // Check for reservations
        $hasReservation = \App\Models\Reservation::where('resource_id', $loan->copy->resource_id)
            ->where('status', 'pending')
            ->exists();

        if ($hasReservation) {
            return new JsonResponse([
                'message' => 'Cannot renew: item has pending reservations.',
            ], 400);
        }

        // Renew the loan (extend by 14 days)
        $loan->due_date = now()->addDays(14);
        $loan->renewal_count += 1;
        $loan->save();

        return new JsonResponse([
            'message' => 'Loan renewed successfully.',
            'loan' => LoanResource::make($loan->load('copy.resource')),
        ]);
    }
}
