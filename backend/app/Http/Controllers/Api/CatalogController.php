<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of resources with filtering.
     */
    public function index(Request $request)
    {
        $query = Resource::query()->with(['category', 'author', 'publisher', 'copies']);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by author
        if ($request->has('author_id')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('authors.id', $request->author_id);
            });
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('resource_type', $request->type);
        }

        // Search by title or ISBN
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by availability
        if ($request->has('available_only') && $request->boolean('available_only')) {
            $query->whereHas('copies', function ($q) {
                $q->where('status', 'available');
            })->withCount(['copies as available_copies' => function ($q) {
                $q->where('status', 'available');
            }])->having('available_copies', '>', 0);
        }

        // Order by
        $orderBy = $request->get('order_by', 'created_at');
        $orderDir = $request->get('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);

        // Paginate
        $perPage = $request->get('per_page', 12);
        $resources = $query->paginate($perPage);

        return ResourceResource::collection($resources);
    }

    /**
     * Display a specific resource.
     */
    public function show(Request $request, $id)
    {
        $resource = Resource::with(['category', 'author', 'publisher', 'copies'])
            ->findOrFail($id);

        return ResourceResource::make($resource);
    }
}
