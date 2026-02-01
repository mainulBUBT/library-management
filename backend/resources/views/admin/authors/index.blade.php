@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Authors</h1>
        <p class="text-gray-500 mt-1">Manage resource authors</p>
    </div>
    <a href="{{ route('admin.authors.create') }}" class="btn-primary inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Author
    </a>
</div>

<!-- Search -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="search" name="search" placeholder="Search by name..."
                       class="form-input" value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-secondary">Search</button>
            @if(request()->has('search'))
                <a href="{{ route('admin.authors.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Authors Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="table-head">
                <tr>
                    <th class="table-th">Author</th>
                    <th class="table-th">Nationality</th>
                    <th class="table-th">Resources</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($authors as $author)
                    <tr class="table-tr">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <span class="text-purple-600 font-semibold">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $author->name }}</div>
                                    @if($author->birth_date)
                                        <div class="text-xs text-gray-500">Born {{ $author->birth_date->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="table-td">
                            {{ $author->nationality ?? '-' }}
                        </td>
                        <td class="table-td">
                            <span class="badge badge-secondary">{{ $author->resources_count }}</span>
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.authors.edit', $author) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this author?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="table-td text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="font-medium">No authors found</p>
                            <p class="text-sm mt-1">Get started by adding your first author</p>
                            <a href="{{ route('admin.authors.create') }}" class="btn-primary inline-block mt-3">
                                Add Author
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($authors->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing {{ $authors->firstItem() }} to {{ $authors->lastItem() }} of {{ $authors->total() }} results
            </div>
            {{ $authors->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
