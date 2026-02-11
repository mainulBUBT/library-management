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

<div class="card">
    <form method="POST" action="{{ route('admin.resources.update', $resource) }}"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="p-6 space-y-6">
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

            <!-- Cover Image -->
            <div>
                <label class="form-label">Cover Image</label>

                <!-- Upload Zone -->
                <div id="upload-zone-edit" class="relative group">
                    <input type="file" name="cover_image" id="cover-image-input-edit"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           accept="image/jpeg,image/png,image/jpg,image/gif"
                           onchange="handleFileSelect(this, 'edit')">

                    <!-- Current Image State -->
                    @if($resource->cover_image)
                        <div id="current-image-edit" class="relative rounded-xl overflow-hidden shadow-lg group">
                            <img src="{{ asset('storage/' . $resource->cover_image) }}"
                                 alt="{{ $resource->title }}"
                                 class="w-full h-64 object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-center gap-2">
                                    <button type="button" onclick="confirmRemoveImage()"
                                            class="bg-red-500/90 backdrop-blur-sm hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Remove Current
                                    </button>
                                    <button type="button" onclick="triggerFileInput()"
                                            class="bg-white/90 backdrop-blur-sm hover:bg-white text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Replace Image
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Default State (No existing image) -->
                        <div id="upload-default-edit" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-200 group-hover:border-indigo-400 group-hover:bg-indigo-50/50">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-indigo-200">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Drop your cover image here</p>
                                <p class="text-xs text-gray-500 mb-2">or</p>
                                <button type="button" onclick="triggerFileInput()"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003 3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Browse Files
                                </button>
                                <p class="text-xs text-gray-400 mt-2">Maximum file size: 2MB</p>
                            </div>
                        </div>
                    @endif

                    <!-- New Image Preview State (Hidden by default) -->
                    <div id="upload-preview-edit" class="hidden relative rounded-xl overflow-hidden shadow-lg max-w-md">
                        <img id="preview-img-edit" src="" alt="Preview" class="w-full h-64 object-contain">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-center gap-2">
                                <button type="button" onclick="clearImage('edit')"
                                        class="bg-white/90 backdrop-blur-sm hover:bg-white text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Cancel New Image
                                </button>
                            </div>
                        </div>
                        <p class="text-center text-sm text-gray-600 mt-2 font-medium">New cover image ready to upload</p>
                    </div>

                <!-- Hidden input for remove flag -->
                <input type="hidden" name="remove_cover_image" id="remove_cover_image" value="0">

                @error('cover_image')
                    <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm9-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
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
<script>
const uploadZoneEdit = document.getElementById('upload-zone-edit');
const currentImageEdit = document.getElementById('current-image-edit');
const uploadDefaultEdit = document.getElementById('upload-default-edit');
const uploadPreviewEdit = document.getElementById('upload-preview-edit');
const previewImgEdit = document.getElementById('preview-img-edit');
const fileInputEdit = document.getElementById('cover-image-input-edit');
const removeImageInput = document.getElementById('remove_cover_image');

// Drag and drop handlers
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadZoneEdit.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadZoneEdit.addEventListener(eventName, () => {
        if (uploadDefaultEdit) {
            uploadDefaultEdit.classList.add('border-indigo-500', 'bg-indigo-50');
        }
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadZoneEdit.addEventListener(eventName, () => {
        if (uploadDefaultEdit) {
            uploadDefaultEdit.classList.remove('border-indigo-500', 'bg-indigo-50');
        }
    }, false);
});

uploadZoneEdit.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    if (files.length > 0) {
        fileInputEdit.files = files;
        handleFileSelect(fileInputEdit, 'edit');
    }
}, false);

function triggerFileInput() {
    fileInputEdit.click();
}

function handleFileSelect(input, type) {
    const file = input.files[0];
    if (!file) return;

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    if (!validTypes.includes(file.type)) {
        alert('Please select a valid image file (JPEG, PNG, or GIF)');
        input.value = '';
        return;
    }

    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        input.value = '';
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (type === 'edit') {
            previewImgEdit.src = e.target.result;
            if (currentImageEdit) {
                currentImageEdit.classList.add('hidden');
            }
            if (uploadDefaultEdit) {
                uploadDefaultEdit.classList.add('hidden');
            }
            uploadPreviewEdit.classList.remove('hidden');
            // Reset remove flag since we're replacing
            removeImageInput.value = '0';
        } else if (type === 'create') {
            previewImgCreate.src = e.target.result;
            uploadDefaultCreate.classList.add('hidden');
            uploadPreviewCreate.classList.remove('hidden');
        }
    };
    reader.readAsDataURL(file);
}

function clearImage(type) {
    if (type === 'edit') {
        fileInputEdit.value = '';
        previewImgEdit.src = '';
        uploadPreviewEdit.classList.add('hidden');
        // Show the original current image again
        if (currentImageEdit) {
            currentImageEdit.classList.remove('hidden');
        }
    } else if (type === 'create') {
        fileInputCreate.value = '';
        previewImgCreate.src = '';
        uploadDefaultCreate.classList.remove('hidden');
        uploadPreviewCreate.classList.add('hidden');
    }
}

function confirmRemoveImage() {
    if (confirm('Are you sure you want to remove the current cover image?')) {
        removeImageInput.value = '1';
        // Hide current image and show upload zone
        if (currentImageEdit) {
            currentImageEdit.classList.add('hidden');
        }
        if (uploadDefaultEdit) {
            uploadDefaultEdit.classList.remove('hidden');
        }
    }
}

// Prevent double-triggering when clicking browse button
document.getElementById('upload-zone-edit').addEventListener('click', function(e) {
    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'INPUT') {
        e.stopPropagation();
    }
});
</script>
@endpush
@endsection
