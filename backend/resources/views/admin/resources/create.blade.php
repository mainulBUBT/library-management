@extends('layouts.admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.resources.index') }}" class="hover:text-gray-700">Resources</a>
        <span>/</span>
        <span>Add New</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Add New Resource</h1>
    <p class="text-gray-500 mt-1">Add a new item to the library catalog</p>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.resources.store') }}"
          enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="p-6 space-y-6">
            <!-- Title & Cover Image -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <label class="form-label">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" class="form-input" required
                           value="{{ old('title') }}" autofocus>
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Cover Image</label>

                    <!-- Default Upload State -->
                    <div id="upload-create-container" class="border-2 border-dashed border-gray-300 rounded-lg p-3 text-center transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/50 relative">
                        <div id="upload-default-create" class="flex flex-col items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-2 shadow-md">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-700">Upload cover</p>
                            <div class="text-xs text-gray-500 mt-1">JPEG, PNG (max 2MB)</div>
                            <input type="file" name="cover_image" id="cover-image-input-create"
                                   class="hidden"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   onchange="handleFileSelect(this, 'create')">
                            <button type="button" onclick="document.getElementById('cover-image-input-create').click()"
                                    class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003 3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Browse
                            </button>
                        </div>
                        
                        <!-- Preview State (Hidden by default) -->
                        <div id="upload-preview-create" class="hidden text-center">
                            <div class="relative inline-block group">
                                <img id="preview-img-create" src="" alt="Preview" class="h-20 w-20 object-cover rounded-md shadow-sm border border-gray-200">
                                <button type="button" onclick="clearImage('create')"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-10"
                                        title="Remove image">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                             <p id="file-name-create" class="text-xs text-gray-500 mt-2 truncate w-full max-w-[10rem] mx-auto"></p>
                        </div>
                    </div>

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
                           value="{{ old('isbn') }}" placeholder="978-0-123456-78-9">
                    @error('isbn')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Resource Type <span class="text-red-500">*</span></label>
                    <select name="resource_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="book" {{ old('resource_type') == 'book' ? 'selected' : '' }}>Book</option>
                        <option value="journal" {{ old('resource_type') == 'journal' ? 'selected' : '' }}>Journal</option>
                        <option value="magazine" {{ old('resource_type') == 'magazine' ? 'selected' : '' }}>Magazine</option>
                        <option value="dvd" {{ old('resource_type') == 'dvd' ? 'selected' : '' }}>DVD</option>
                        <option value="cd" {{ old('resource_type') == 'cd' ? 'selected' : '' }}>CD</option>
                        <option value="ebook" {{ old('resource_type') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                        <option value="audiobook" {{ old('resource_type') == 'audiobook' ? 'selected' : '' }}>Audiobook</option>
                        <option value="research_paper" {{ old('resource_type') == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Resource Type <span class="text-red-500">*</span></label>
                    <select name="resource_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option value="book" {{ old('resource_type') == 'book' ? 'selected' : '' }}>Book</option>
                        <option value="journal" {{ old('resource_type') == 'journal' ? 'selected' : '' }}>Journal</option>
                        <option value="magazine" {{ old('resource_type') == 'magazine' ? 'selected' : '' }}>Magazine</option>
                        <option value="dvd" {{ old('resource_type') == 'dvd' ? 'selected' : '' }}>DVD</option>
                        <option value="cd" {{ old('resource_type') == 'cd' ? 'selected' : '' }}>CD</option>
                        <option value="ebook" {{ old('resource_type') == 'ebook' ? 'selected' : '' }}>E-Book</option>
                        <option value="audiobook" {{ old('resource_type') == 'audiobook' ? 'selected' : '' }}>Audiobook</option>
                        <option value="research_paper" {{ old('resource_type') == 'research_paper' ? 'selected' : '' }}>Research Paper</option>
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Publisher</label>
                    <select name="publisher_id" class="form-select">
                        <option value="">Select publisher...</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
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
                           value="{{ old('publication_year') }}" min="1000" max="{{ date('Y') + 1 }}">
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
                           value="{{ old('language') ?? 'en' }}" placeholder="en">
                    @error('language')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Number of Pages</label>
                    <input type="number" name="pages" class="form-input"
                           value="{{ old('pages') }}" min="1">
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
                        <option value="{{ $author->id }}">
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
                          placeholder="Brief description of the resource...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Copies Count -->
            <div>
                <label class="form-label">Number of Copies <span class="text-red-500">*</span></label>
                <input type="number" name="copies_count" class="form-input"
                       value="{{ old('copies_count') ?? 1 }}" min="1" max="100" required>
                <p class="text-xs text-gray-500 mt-1">How many physical copies should be created?</p>
                @error('copies_count')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.resources.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Resource</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
.image-overlay-container .image-overlay {
    opacity: 0;
}
.image-overlay-container:hover .image-overlay {
    opacity: 1;
}
.select2-form-input .select2-selection {
    border-color: #d1d5db;
    min-height: 38px;
}
.select2-form-input .select2-selection--single {
    display: flex;
    align-items: center;
}
</style>
<script>
const uploadDefaultCreate = document.getElementById('upload-default-create');
const uploadPreviewCreate = document.getElementById('upload-preview-create');
const previewImgCreate = document.getElementById('preview-img-create');
const fileNameCreate = document.getElementById('file-name-create');
const fileInputCreate = document.getElementById('cover-image-input-create');

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
        if (type === 'create') clearImage(type);
        return;
    }

    // Validate file size (2MB = 2 * 1024 * 1024 bytes)
    if (file.size > 2 * 1024 * 1024) {
        alert('File size must be less than 2MB');
        input.value = '';
        if (type === 'create') clearImage(type);
        return;
    }

// Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (type === 'create') {
            previewImgCreate.src = e.target.result;
            fileNameCreate.textContent = file.name;
            uploadDefaultCreate.classList.add('hidden');
            uploadPreviewCreate.classList.remove('hidden');
            // Remove dashed border when preview is active
            document.getElementById('upload-create-container').classList.remove('border-dashed');
        }
    };
    reader.readAsDataURL(file);
}

function clearImage(type) {
    if (type === 'create') {
        fileInputCreate.value = '';
        previewImgCreate.src = '';
        fileNameCreate.textContent = '';
        uploadDefaultCreate.classList.remove('hidden');
        uploadPreviewCreate.classList.add('hidden');
        // Add dashed border back
        document.getElementById('upload-create-container').classList.add('border-dashed');
    }
}
</script>
@endpush
@endsection
