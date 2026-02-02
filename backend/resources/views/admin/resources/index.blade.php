@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Resources</h1>
        <p class="text-gray-500 mt-1">Manage your library catalog</p>
    </div>
    <a href="{{ route('admin.resources.create') }}" class="btn-primary inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Resource
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.resources.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <input type="search" name="search" placeholder="Search by title or ISBN..."
                       class="form-input" value="{{ request('search') }}">
            </div>
            <div class="w-full lg:w-48">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full lg:w-48">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Book</option>
                    <option value="journal" {{ request('type') == 'journal' ? 'selected' : '' }}>Journal</option>
                    <option value="magazine" {{ request('type') == 'magazine' ? 'selected' : '' }}>Magazine</option>
                    <option value="dvd" {{ request('type') == 'dvd' ? 'selected' : '' }}>DVD</option>
                    <option value="cd" {{ request('type') == 'cd' ? 'selected' : '' }}>CD</option>
                    <option value="ebook" {{ request('type') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                    <option value="audiobook" {{ request('type') == 'audiobook' ? 'selected' : '' }}>Audiobook</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'category', 'type']))
                <a href="{{ route('admin.resources.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Resources Table -->
<div class="card">
    <div class="overflow-x-auto">
        <div class="table-wrapper">
        <table class="table">
            <thead class="table-head">
                <tr>
                    <th class="table-th">Resource</th>
                    <th class="table-th">Type</th>
                    <th class="table-th">Category</th>
                    <th class="table-th">Copies</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                    <tr class="table-tr">
                        <td class="table-td">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $resource->title }}</div>
                                    @if($resource->authors->count() > 0)
                                        <div class="text-xs text-gray-500">{{ $resource->authors->pluck('name')->implode(', ') }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="table-td">
                            <span class="badge badge-secondary">{{ ucfirst($resource->resource_type) }}</span>
                        </td>
                        <td class="table-td">
                            {{ $resource->category?->name ?? '-' }}
                        </td>
                        <td class="table-td">
                            <div class="text-sm">
                                <span class="font-medium">{{ $resource->copies->count() }}</span> total
                                <span class="text-gray-500">({{ $resource->copies->where('status', 'available')->count() }} available)</span>
                            </div>
                        </td>
                        <td class="table-td">
                            @if($resource->status === 'available')
                                <span class="badge badge-success">Available</span>
                            @elseif($resource->status === 'unavailable')
                                <span class="badge badge-warning">Unavailable</span>
                            @else
                                <span class="badge badge-secondary">Archived</span>
                            @endif
                        </td>
                        <td class="table-td text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.resources.show', $resource) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.resources.edit', $resource) }}"
                                   class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this resource?');"
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
                        <td colspan="6" class="table-td text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="font-medium">No resources found</p>
                            <p class="text-sm mt-1">Get started by adding your first resource</p>
                            <a href="{{ route('admin.resources.create') }}" class="btn-primary inline-block mt-3">
                                Add Resource
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($resources->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                                Showing {{ $resources->firstItem() }} to {{ $resources->lastItem() }} of {{ $resources->total() }} results
                            </div>
                            {{ $resources->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
@endsection
