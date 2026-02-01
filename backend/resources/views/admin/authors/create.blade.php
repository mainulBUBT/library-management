@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.authors.index') }}" class="hover:text-gray-700">Authors</a>
        <span>/</span>
        <span>Add New</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Add Author</h1>
    <p class="text-gray-500 mt-1">Add a new author to the library</p>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.authors.store') }}" class="card">
        @csrf

        <div class="card-body space-y-6">
            <div>
                <label class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="form-input" required
                       value="{{ old('name') }}" autofocus>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Nationality</label>
                    <input type="text" name="nationality" class="form-input"
                           value="{{ old('nationality') }}" placeholder="e.g. American">
                    @error('nationality')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Birth Date</label>
                    <input type="date" name="birth_date" class="form-input"
                           value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Website</label>
                <input type="url" name="website_url" class="form-input"
                       value="{{ old('website_url') }}" placeholder="https://">
                @error('website_url')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Biography</label>
                <textarea name="bio" rows="5" class="form-textarea"
                          placeholder="Brief biography of the author...">{{ old('bio') }}</textarea>
                @error('bio')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.authors.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Author</button>
        </div>
    </form>
</div>
@endsection
