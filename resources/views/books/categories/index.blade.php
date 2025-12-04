@extends('layouts.main')

@section('content')
<div class="container">
    <!-- Hidden Filter Card -->
    <div id="filterCard" style="display: none;" class="mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{ __('Filters') }}
                <button type="button" class="btn-close" id="closeFilterCard" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <!-- Server-side Filters -->
                <form method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <input type="text" name="search" class="form-control" placeholder="Search by category name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Category') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="toggleCreateForm">
                            + Add Category
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Create Category Form -->
                    <div id="createForm" class="mb-4 d-none">
                        <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" name="category" class="form-control" placeholder="Enter category name" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <button type="button" class="btn btn-secondary" id="cancelCreate">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table class="table">
                        <thead>
                            <tr id="bulkActionsRow" class="d-none">
                                <th><input type="checkbox" id="selectAll"></th>
                                <th colspan="6" id="bulkActionsCell">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" id="selectAllHeader"></th>
                                <th width="80px">No</th>
                                <th>Category</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td><input type="checkbox" class="category-checkbox" value="{{ $category->id }}"></td>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $category->category }}</td>
                                    <td>{{ $category->creator ? $category->creator->name : 'N/A' }}</td>
                                    <td>{{ $category->updater ? $category->updater->name : 'N/A' }}</td>
                                    <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $category->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('categories.show', $category->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this category?')" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi bi-trash"></i> Delete</button>
                                                </form></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $categories->links() }}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection