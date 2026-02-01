<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Author::withCount('resources');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $authors = $query->orderBy('name')->paginate(20);

        return view('admin.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'website_url' => 'nullable|url|max:255',
        ]);

        Author::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'bio' => $validated['bio'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.authors.index')
            ->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author->load(['resources' => function ($query) {
            $query->with('category')->limit(20);
        }]);

        return view('admin.authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'website_url' => 'nullable|url|max:255',
        ]);

        $author->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'bio' => $validated['bio'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
        ]);

        return redirect()->route('admin.authors.index')
            ->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        if ($author->resources()->count() > 0) {
            return back()->with('error', 'Cannot delete author with resources.');
        }

        $author->delete();

        return redirect()->route('admin.authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}
