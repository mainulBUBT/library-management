<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReservationResource extends JsonResource
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
            'status' => $this->status,
            'reserved_date' => $this->created_at?->format('Y-m-d'),
            'expires_at' => $this->expires_at?->format('Y-m-d'),
            'is_expired' => $this->expires_at && $this->expires_at->isPast(),
            'resource' => $this->whenLoaded('resource', fn() => [
                'id' => $this->resource->id,
                'title' => $this->resource->title,
                'cover_image' => $this->resource->cover_image ? Storage::url($this->resource->cover_image) : null,
                'author' => $this->resource->author?->name,
            ]),
        ];
    }
}
