@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.resources.index') }}" class="hover:text-gray-700">Resources</a>
        <span>/</span>
        <span>Edit</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Edit Resource</h1>
    <p class="text-gray-500 mt-1">Update resource information</p>
</div>

<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.resources.update', $resource) }}" class="card">
        @csrf
        @method('PUT')

        <div class="card-body space-y-6">
            <!-- Title & ISBN -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="form-input" required
                           value="{{ old('title', $resource->title) }}" autofocus>
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-input"
                           value="{{ old('isbn', $resource->isbn) }}" placeholder="978-0-123456-78-9">
                    @error('isbn')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Resource Type & Category -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">Resource Type <span class="text-red-500">*</span></label>
                    <select name="resource_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="book" {{ old('resource_type', $resource->resource_type) == 'book' ? 'selected' : '' }}>Book</option>
                        <option value="journal" {{ old('resource_type', $resource->resource_type) == 'journal' ? 'selected' : '' }}>Journal</option>
                        <option value="magazine" {{ old('resource_type', $resource->resource_type) == 'magazine' ? 'selected' : '' }}>Magazine</option>
                        <option value="dvd" {{ old('resource_type', $resource->resource_type) == 'dvd' ? 'selected' : '' }}>DVD</option>
                        <option value="cd" {{ old('resource_type', $resource->resource_type) == 'cd' ? 'selected' : '' }}>CD</option>
                        <option value="ebook" {{ old('resource_type', $resource->resource_type) == 'ebook' ? 'selected' : '' }}>E-Book</option>
                        <option value="audiobook" {{ old('resource_type', $resource->resource_type) == 'audiobook' ? 'selected' : '' }}>Audiobook</option>
                        <option value="research_paper" {{ old('resource_type', $resource->resource_type) == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
                    </select>
                    @error('resource_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">Select category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $resource->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="available" {{ old('status', $resource->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status', $resource->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="archived" {{ old('status', $resource->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Publisher & Publication Year -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Publisher</label>
                    <select name="publisher_id" class="form-select">
                        <option value="">Select publisher...</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $resource->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('publisher_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Publication Year</label>
                    <input type="number" name="publication_year" class="form-input"
                           value="{{ old('publication_year', $resource->publication_year) }}" min="1000" max="{{ date('Y') + 1 }}">
                    @error('publication_year')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Language & Pages -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Language</label>
                    <input type="text" name="language" class="form-input"
                           value="{{ old('language', $resource->language) }}" placeholder="en">
                    @error('language')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Number of Pages</label>
                    <input type="number" name="pages" class="form-input"
                           value="{{ old('pages', $resource->pages) }}" min="1">
                    @error('pages')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Authors -->
            <div>
                <label class="form-label">Authors</label>
                <select name="authors[]" class="form-select" multiple>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ in_array($author->id, old('authors', $resource->authors->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple authors</p>
                @error('authors')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="4" class="form-textarea"
                          placeholder="Brief description of the resource...">{{ old('description', $resource->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between rounded-b-xl">
            <a href="{{ route('admin.resources.show', $resource) }}" class="text-gray-500 hover:text-gray-700">View resource</a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.resources.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Resource</button>
            </div>
        </div>
    </form>
</div>
@endsection
