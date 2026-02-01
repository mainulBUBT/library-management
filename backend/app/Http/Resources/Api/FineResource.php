<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $amountPaid = $this->whenLoaded('payments', fn() => $this->payments->sum('amount'), 0);

        return [
            'id' => $this->id,
            'amount' => (float) $this->amount,
            'amount_paid' => (float) $amountPaid,
            'balance' => (float) ($this->amount - $amountPaid),
            'status' => $this->status,
            'reason' => $this->reason,
            'fine_date' => $this->created_at?->format('Y-m-d'),
            'loan' => $this->whenLoaded('loan', fn() => [
                'id' => $this->loan->id,
                'due_date' => $this->loan->due_date?->format('Y-m-d'),
                'resource' => $this->whenLoaded('loan.copy', fn() => [
                    'id' => $this->loan->copy->resource->id,
                    'title' => $this->loan->copy->resource->title,
                ]),
            ]),
        ];
    }
}
