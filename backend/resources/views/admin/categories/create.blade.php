@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.categories.index') }}" class="hover:text-gray-700">Categories</a>
        <span>/</span>
        <span>Add New</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Add Category</h1>
    <p class="text-gray-500 mt-1">Create a new catalog category</p>
</div>

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="card">
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

            <div>
                <label class="form-label">Parent Category</label>
                <select name="parent_id" class="form-select">
                    <option value="">None (Top Level)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-textarea"
                          placeholder="Optional description...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Category</button>
        </div>
    </form>
</div>
@endsection
