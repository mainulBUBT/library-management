<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resource::with(['category', 'publisher', 'authors']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('resource_type', $request->type);
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.resources.index', compact('resources', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);
        $authors = Author::orderBy('name')->get(['id', 'name']);

        return view('admin.resources.create', compact('categories', 'publishers', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:resources,isbn',
            'resource_type' => 'required|in:book,journal,magazine,dvd,cd,research_paper,ebook,audiobook',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:10',
            'pages' => 'nullable|integer|min:1',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'copies_count' => 'required|integer|min:1|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/covers', $imageName);
            $coverImagePath = 'covers/' . $imageName;
        }

        $resource = Resource::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'isbn' => $validated['isbn'],
            'resource_type' => $validated['resource_type'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'pages' => $validated['pages'] ?? null,
            'cover_image' => $coverImagePath,
        ]);

        // Attach authors
        if (!empty($validated['authors'])) {
            foreach ($validated['authors'] as $authorId) {
                $resource->authors()->attach($authorId, ['role' => 'author']);
            }
        }

        // Create copies
        for ($i = 1; $i <= $validated['copies_count']; $i++) {
            $resource->copies()->create([
                'copy_number' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'barcode' => $resource->id . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => 'available',
                'condition' => 'new',
            ]);
        }

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        $resource->load(['category', 'publisher', 'authors', 'copies' => function ($query) {
            $query->with('activeLoan.member.user');
        }]);

        return view('admin.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $publishers = Publisher::orderBy('name')->get(['id', 'name']);
        $authors = Author::orderBy('name')->get(['id', 'name']);
        $resource->load('authors');

        return view('admin.resources.edit', compact('resource', 'categories', 'publishers', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => ['nullable', 'string', Rule::unique('resources')->ignore($resource->id)],
            'resource_type' => 'required|in:book,journal,magazine,dvd,cd,research_paper,ebook,audiobook',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'language' => 'nullable|string|max:10',
            'pages' => 'nullable|integer|min:1',
            'status' => 'required|in:available,unavailable,archived',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_cover_image' => 'nullable|boolean',
        ]);

        // Handle image upload or removal
        $coverImagePath = $resource->cover_image;
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($resource->cover_image && Storage::exists('public/' . $resource->cover_image)) {
                Storage::delete('public/' . $resource->cover_image);
            }
            // Upload new image
            $image = $request->file('cover_image');
            $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/covers', $imageName);
            $coverImagePath = 'covers/' . $imageName;
        } elseif (!empty($validated['remove_cover_image'])) {
            // Remove image
            if ($resource->cover_image && Storage::exists('public/' . $resource->cover_image)) {
                Storage::delete('public/' . $resource->cover_image);
            }
            $coverImagePath = null;
        }

        $resource->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'isbn' => $validated['isbn'],
            'resource_type' => $validated['resource_type'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'publisher_id' => $validated['publisher_id'] ?? null,
            'publication_year' => $validated['publication_year'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'pages' => $validated['pages'] ?? null,
            'status' => $validated['status'],
            'cover_image' => $coverImagePath,
        ]);

        // Sync authors
        if (isset($validated['authors'])) {
            $authorData = [];
            foreach ($validated['authors'] as $authorId) {
                $authorData[$authorId] = ['role' => 'author'];
            }
            $resource->authors()->sync($authorData);
        }

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        // Check if resource has active loans
        $activeLoans = $resource->copies()->whereHas('activeLoan')->count();
        if ($activeLoans > 0) {
            return back()->with('error', 'Cannot delete resource with active loans.');
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}
