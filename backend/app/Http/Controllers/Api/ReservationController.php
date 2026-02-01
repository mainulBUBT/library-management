<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ReservationResource;
use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Get authenticated user's reservations.
     */
    public function myReservations(Request $request)
    {
        $user = $request->user();

        $reservations = Reservation::with(['resource', 'resource.category', 'resource.author'])
            ->where('member_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return ReservationResource::collection($reservations);
    }

    /**
     * Create a new reservation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
        ]);

        $user = $request->user();

        // Check if user already has active loan for this resource
        $hasActiveLoan = \App\Models\Loan::where('member_id', $user->id)
            ->where('status', 'active')
            ->whereHas('copy', function ($q) use ($request) {
                $q->where('resource_id', $request->resource_id);
            })
            ->exists();

        if ($hasActiveLoan) {
            return new JsonResponse([
                'message' => 'You already have an active loan for this item.',
            ], 400);
        }

        // Check if user already has pending reservation
        $existingReservation = Reservation::where('member_id', $user->id)
            ->where('resource_id', $request->resource_id)
            ->where('status', 'pending')
            ->first();

        if ($existingReservation) {
            return new JsonResponse([
                'message' => 'You already have a pending reservation for this item.',
            ], 400);
        }

        $resource = Resource::findOrFail($request->resource_id);

        $reservation = Reservation::create([
            'member_id' => $user->id,
            'resource_id' => $request->resource_id,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return new JsonResponse([
            'message' => 'Reservation created successfully.',
            'reservation' => ReservationResource::make($reservation->load('resource')),
        ], 201);
    }

    /**
     * Cancel a reservation.
     */
    public function cancel(Request $request, $id)
    {
        $user = $request->user();

        $reservation = Reservation::where('member_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($reservation->status === 'fulfilled') {
            return new JsonResponse([
                'message' => 'Cannot cancel a fulfilled reservation.',
            ], 400);
        }

        $reservation->status = 'cancelled';
        $reservation->save();

        return new JsonResponse([
            'message' => 'Reservation cancelled successfully.',
        ]);
    }
}
