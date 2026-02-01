<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FineResource;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Get authenticated user's fines.
     */
    public function myFines(Request $request)
    {
        $user = $request->user();

        $fines = Fine::with(['loan', 'loan.copy', 'loan.copy.resource', 'payments'])
            ->whereHas('loan', function ($q) use ($user) {
                $q->where('member_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $fines->getCollection()->transform(function ($fine) {
            $fine->amount_paid = $fine->payments->sum('amount');
            $fine->balance = $fine->amount - $fine->amount_paid;
            return $fine;
        });

        return FineResource::collection($fines);
    }
}
