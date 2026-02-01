<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'loan_date' => $this->loan_date?->format('Y-m-d'),
            'due_date' => $this->due_date?->format('Y-m-d'),
            'return_date' => $this->return_date?->format('Y-m-d'),
            'status' => $this->status,
            'renewal_count' => $this->renewal_count,
            'is_overdue' => $this->due_date && $this->due_date->isPast() && $this->status === 'active',
            'days_until_due' => $this->due_date ? now()->diffInDays($this->due_date, false) : null,
            'copy' => $this->whenLoaded('copy', fn() => [
                'id' => $this->copy->id,
                'barcode' => $this->copy->barcode,
                'call_number' => $this->copy->call_number,
                'resource' => $this->whenLoaded('copy.resource', fn() => [
                    'id' => $this->copy->resource->id,
                    'title' => $this->copy->resource->title,
                    'cover_image' => $this->copy->resource->cover_image,
                ]),
            ]),
            'fine' => $this->whenLoaded('fine', fn() => [
                'id' => $this->fine->id ?? null,
                'amount' => $this->fine->amount ?? null,
                'status' => $this->fine->status ?? null,
            ]),
        ];
    }
}
