@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.resources.index') }}" class="hover:text-gray-700">Resources</a>
        <span>/</span>
        <span>{{ $resource->title }}</span>
    </div>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $resource->title }}</h1>
            <p class="text-gray-500 mt-1">
                @if($resource->authors->count() > 0)
                    by {{ $resource->authors->pluck('name')->implode(', ') }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.resources.edit', $resource) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>
</div>

<!-- Resource Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Resource Information</h2>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->isbn ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <span class="badge badge-secondary">{{ ucfirst($resource->resource_type) }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->category?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Publisher</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->publisher?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Publication Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->publication_year ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Language</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($resource->language ?? 'en') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pages</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $resource->pages ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($resource->status === 'available')
                                <span class="badge badge-success">Available</span>
                            @elseif($resource->status === 'unavailable')
                                <span class="badge badge-warning">Unavailable</span>
                            @else
                                <span class="badge badge-secondary">Archived</span>
                            @endif
                        </dd>
                    </div>
                </dl>
                @if($resource->description)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $resource->description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Copies -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Copies ({{ $resource->copies->count() }})</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-wrapper">
                <table class="table">
                    <thead class="table-head">
                        <tr>
                            <th class="table-th">Copy #</th>
                            <th class="table-th">Barcode</th>
                            <th class="table-th">Condition</th>
                            <th class="table-th">Status</th>
                            <th class="table-th">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resource->copies as $copy)
                            <tr class="table-tr">
                                <td class="table-td">{{ $copy->copy_number }}</td>
                                <td class="table-td">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $copy->barcode }}</code>
                                </td>
                                <td class="table-td">{{ ucfirst($copy->condition) }}</td>
                                <td class="table-td">
                                    @if($copy->status === 'available')
                                        <span class="badge badge-success">Available</span>
                                    @elseif($copy->status === 'borrowed')
                                        <span class="badge badge-warning">Borrowed</span>
                                    @elseif($copy->status === 'reserved')
                                        <span class="badge badge-info">Reserved</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($copy->status) }}</span>
                                    @endif
                                </td>
                                <td class="table-td">
                                    @if($copy->activeLoan)
                                        <span class="text-sm">{{ $copy->activeLoan->due_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Stats -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Statistics</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Copies</span>
                    <span class="text-lg font-semibold">{{ $resource->copies->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Available</span>
                    <span class="text-lg font-semibold text-green-600">{{ $resource->copies->where('status', 'available')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Borrowed</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $resource->copies->where('status', 'borrowed')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Reserved</span>
                    <span class="text-lg font-semibold text-blue-600">{{ $resource->copies->where('status', 'reserved')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Quick Actions</h2>
            </div>
            <div class="card-body space-y-3">
                <a href="{{ route('admin.loans.create', ['resource' => $resource->id]) }}"
                   class="btn-primary w-full text-center">
                    Checkout Copy
                </a>
                <a href="{{ route('admin.reservations.create', ['resource' => $resource->id]) }}"
                   class="btn-secondary w-full text-center">
                    Create Reservation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
