@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

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

<div class="card">
    <form method="POST" action="{{ route('admin.resources.update', $resource) }}"
          enctype="multipart/form-data" class="space-y-6" id="edit-resource-form">
        @csrf
        @method('PUT')

        <div class="p-6 space-y-6">
            <!-- Title & Cover Image -->
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
                    <label class="form-label">Cover Image</label>

                    <!-- Hidden file input (always in form, outside conditional display) -->
                    <input type="file" name="cover_image" id="cover-image-input-edit"
                           class="hidden"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           onchange="handleFileSelect(this, 'edit')">

                    <!-- Upload Container (for display only) -->
                    <div id="upload-edit-container" class="border-2 border-dashed border-gray-300 rounded-lg p-3 text-center transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/50 relative">
                        <!-- Current Image State -->
                        @if($resource->cover_image)
                            <div id="upload-current-edit" class="text-center">
                                <div class="inline-block">
                                    <img src="{{ asset('storage/' . $resource->cover_image) }}"
                                         alt="{{ $resource->title }}"
                                         class="h-20 w-20 object-cover rounded-md shadow-sm border border-gray-200">
                                </div>
                                <div class="mt-2 space-y-1">
                                    <p class="text-xs text-gray-500">Current cover</p>
                                    <button type="button" onclick="document.getElementById('cover-image-input-edit').click()"
                                            class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-all duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003 3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Replace
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Default Upload State -->
                            <div id="upload-default-edit" class="flex flex-col items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-2 shadow-md">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-medium text-gray-700">Upload cover</p>
                                <div class="text-xs text-gray-500 mt-1">JPEG, PNG (max 2MB)</div>
                                <button type="button" onclick="document.getElementById('cover-image-input-edit').click()"
                                        class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003 3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Browse
                                </button>
                            </div>
                        @endif

                        <!-- Preview State (Hidden by default) -->
                        <div id="upload-preview-edit" class="hidden text-center">
                            <div class="relative inline-block group">
                                <img id="preview-img-edit" src="" alt="Preview" class="h-20 w-20 object-cover rounded-md shadow-sm border border-gray-200">
                                <button type="button" onclick="clearImage('edit')"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-10"
                                        title="Remove image">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <p id="file-name-edit" class="text-xs text-gray-500 mt-2 truncate w-full max-w-[10rem] mx-auto"></p>
                        </div>
                    </div>

                    <!-- Hidden input for remove flag -->
                    <input type="hidden" name="remove_cover_image" id="remove_cover_image" value="0">

                    @error('cover_image')
                        <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm9-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- ISBN, Resource Type & Category -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-input"
                           value="{{ old('isbn', $resource->isbn) }}" placeholder="978-0-123456-78-9">
                    @error('isbn')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
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
            </div>

            <!-- Publisher & Publication Year -->
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
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.resources.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Resource</button>
        </div>
    </form>
</div>

@push('scripts')
<style>
.select2-dropdown-custom {
    z-index: 9999;
}
.select2-container .select2-selection {
    border-color: #d1d5db;
    min-height: 38px;
}
.select2-container--default .select2-selection--single {
    display: flex;
    align-items: center;
}
.select2-dropdown {
    border-color: #d1d5db;
}
</style>
<script>
const uploadCurrentEdit = document.getElementById('upload-current-edit');
const uploadDefaultEdit = document.getElementById('upload-default-edit');
const uploadPreviewEdit = document.getElementById('upload-preview-edit');
const previewImgEdit = document.getElementById('preview-img-edit');
const fileNameEdit = document.getElementById('file-name-edit');
const fileInputEdit = document.getElementById('cover-image-input-edit');
const removeImageInput = document.getElementById('remove_cover_image');

// Initialize Select2
document.addEventListener('DOMContentLoaded', function() {
    $('select:not([multiple])').select2({
        theme: 'default',
        width: '100%',
        dropdownCssClass: 'select2-dropdown-custom'
    });

    $('select[multiple]').select2({
        theme: 'default',
        width: '100%',
        dropdownCssClass: 'select2-dropdown-custom'
    });
});

function handleFileSelect(input, type) {
    const file = input.files[0];
    if (!file) return;

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        alert('Please select a valid image file (JPEG, PNG, or GIF)');
        input.value = '';
        if (type === 'edit') clearImage(type);
        return;
    }

    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        input.value = '';
        if (type === 'edit') clearImage(type);
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (type === 'edit') {
            previewImgEdit.src = e.target.result;
            fileNameEdit.textContent = file.name;
            if (uploadCurrentEdit) {
                uploadCurrentEdit.classList.add('hidden');
            }
            if (uploadDefaultEdit) {
                uploadDefaultEdit.classList.add('hidden');
            }
            uploadPreviewEdit.classList.remove('hidden');
            // Remove dashed border when preview is active
            document.getElementById('upload-edit-container').classList.remove('border-dashed');
            // Reset remove flag since we're replacing
            removeImageInput.value = '0';
        }
    };
    reader.readAsDataURL(file);
}

function clearImage(type) {
    if (type === 'edit') {
        fileInputEdit.value = '';
        previewImgEdit.src = '';
        fileNameEdit.textContent = '';
        uploadPreviewEdit.classList.add('hidden');
        // Show the original current image again or default state
        if (uploadCurrentEdit) {
            uploadCurrentEdit.classList.remove('hidden');
        } else {
            uploadDefaultEdit.classList.remove('hidden');
        }
        // Add dashed border back
        document.getElementById('upload-edit-container').classList.add('border-dashed');
    }
}

function confirmRemoveImage() {
    if (confirm('Are you sure you want to remove the current cover image?')) {
        removeImageInput.value = '1';
        // Hide current image and show default upload state
        if (uploadCurrentEdit) {
            uploadCurrentEdit.classList.add('hidden');
        }
        uploadDefaultEdit.classList.remove('hidden');
        // Add dashed border back
        document.getElementById('upload-edit-container').classList.add('border-dashed');
    }
}
</script>
@endpush
@endsection
