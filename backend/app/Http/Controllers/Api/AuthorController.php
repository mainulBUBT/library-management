<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index()
    {
        $authors = Author::orderBy('last_name')->orderBy('first_name')->get();

        return response()->json([
            'authors' => $authors->map(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->full_name,
                    'biography' => $author->biography,
                ];
            }),
        ]);
    }

    /**
     * Display a specific author.
     */
    public function show($id)
    {
        $author = Author::with('resources')->findOrFail($id);

        return response()->json([
            'author' => [
                'id' => $author->id,
                'first_name' => $author->first_name,
                'last_name' => $author->last_name,
                'full_name' => $author->full_name,
                'biography' => $author->biography,
                'resources_count' => $author->resources->count(),
            ],
        ]);
    }
}
