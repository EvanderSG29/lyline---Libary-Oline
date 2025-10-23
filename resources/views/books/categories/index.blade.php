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

                <!-- Client-side Search -->
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Client-side search...">
                        <button class="btn btn-outline-secondary d-none" type="button" id="clearButton">
                            <i class="bi bi-x"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Category') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleFilterCard">
                            <i class="bi bi-funnel-fill fs-6"></i> Filters
                        </button>
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

@push('scripts')
<script>
    // Toggle filter card functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggleFilterBtn = document.getElementById('toggleFilterCard');
        const filterCard = document.getElementById('filterCard');
        const closeFilterBtn = document.getElementById('closeFilterCard');

        if (toggleFilterBtn) {
            toggleFilterBtn.addEventListener('click', function() {
                if (filterCard.style.display === 'none' || filterCard.style.display === '') {
                    filterCard.style.display = 'block';
                } else {
                    filterCard.style.display = 'none';
                }
            });
        }

        if (closeFilterBtn) {
            closeFilterBtn.addEventListener('click', function() {
                filterCard.style.display = 'none';
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Cari alert berdasarkan ID
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            // Sembunyikan alert setelah 5 detik
            setTimeout(() => {
                new bootstrap.Alert(successAlert).close();
            }, 5000);
        }
    });

    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActionsRow = document.getElementById('bulkActionsRow');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActionsRow.classList.remove('d-none');
        } else {
            bulkActionsRow.classList.add('d-none');
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllHeaderCheckbox.checked = this.checked;
        updateBulkActions();
    });

    selectAllHeaderCheckbox.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        selectAllCheckbox.checked = this.checked;
        updateBulkActions();
    });

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
            const allChecked = checkedBoxes.length === categoryCheckboxes.length;
            const someChecked = checkedBoxes.length > 0;

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            selectAllHeaderCheckbox.checked = allChecked;
            selectAllHeaderCheckbox.indeterminate = someChecked && !allChecked;
            updateBulkActions();
        });
    });

    // Bulk delete functionality
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.category-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one category to delete.');
            return;
        }

        if (confirm('Are you sure you want to delete the selected categories?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("categories.bulkDelete") }}';

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Method spoofing for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Category IDs
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'category_ids[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });

    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearButton');
    const searchButton = document.getElementById('searchButton');

    // Toggle clear button visibility
    searchInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            clearButton.classList.remove('d-none');
        } else {
            clearButton.classList.add('d-none');
        }
    });

    // Clear input on clear button click
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('d-none');
        // Reset table display
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });

    // Search functionality on button click
    searchButton.addEventListener('click', function() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Toggle create form functionality
    const toggleCreateFormBtn = document.getElementById('toggleCreateForm');
    const createForm = document.getElementById('createForm');
    const cancelCreateBtn = document.getElementById('cancelCreate');
    const categoryForm = document.getElementById('categoryForm');

    toggleCreateFormBtn.addEventListener('click', function() {
        if (createForm.classList.contains('d-none')) {
            createForm.classList.remove('d-none');
            toggleCreateFormBtn.textContent = 'Hide Form';
            // Focus on the input field
            createForm.querySelector('input[name="category"]').focus();
        } else {
            createForm.classList.add('d-none');
            toggleCreateFormBtn.textContent = '+ Add Category';
            // Reset form
            categoryForm.reset();
        }
    });

    cancelCreateBtn.addEventListener('click', function() {
        createForm.classList.add('d-none');
        toggleCreateFormBtn.textContent = '+ Add Category';
        categoryForm.reset();
    });
</script>
@endpush
@endsection