<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Storage;

class ResourceResource extends JsonResource
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'isbn' => $this->isbn,
            'publication_year' => $this->publication_year,
            'resource_type' => $this->resource_type,
            'cover_image' => $this->cover_image ? Storage::url($this->cover_image) : null,
            'category' => $this->whenLoaded('category', fn() => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ]),
            'author' => $this->whenLoaded('author', fn() => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ]),
            'authors' => $this->whenLoaded('authors', fn() => $this->authors->map(fn($author) => [
                'id' => $author->id,
                'name' => $author->name,
                'role' => $author->pivot?->role,
            ])),
            'publisher' => $this->whenLoaded('publisher', fn() => [
                'id' => $this->publisher->id,
                'name' => $this->publisher->name,
            ]),
            'copies_count' => $this->whenLoaded('copies', fn() => $this->copies->count()),
            'available_copies' => $this->whenLoaded('copies', fn() => $this->copies->where('status', 'available')->count()),
            'created_at' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
