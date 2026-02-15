@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.authors.index') }}" class="hover:text-gray-700">Authors</a>
        <span>/</span>
        <span>Edit</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Edit Author</h1>
    <p class="text-gray-500 mt-1">Update author information</p>
</div>

<div class="w-full">
    <form method="POST" action="{{ route('admin.authors.update', $author) }}" class="card">
        @csrf
        @method('PUT')

        <div class="card-body space-y-6">
            <div>
                <label class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="form-input" required
                       value="{{ old('name', $author->name) }}" autofocus>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-input"
                           value="{{ old('nationality', $author->nationality) }}" placeholder="e.g. American">
                    @error('nationality')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Birth Date</label>
                    <input type="date" name="birth_date" class="form-input"
                           value="{{ old('birth_date', $author->birth_date?->format('Y-m-d')) }}">
                    @error('birth_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Website</label>
                <input type="url" name="website_url" class="form-input"
                       value="{{ old('website_url', $author->website_url) }}" placeholder="https://">
                @error('website_url')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Biography</label>
                <textarea name="bio" rows="5" class="form-textarea"
                          placeholder="Brief biography of the author...">{{ old('bio', $author->bio) }}</textarea>
                @error('bio')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.authors.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Author</button>
        </div>
    </form>
</div>
@endsection
