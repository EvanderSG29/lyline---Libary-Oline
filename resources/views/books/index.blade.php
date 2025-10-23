@extends('layouts.main')

@section('content')
<div class="container">
    <!-- Filter Card -->
    <div class="mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{ __('Filters') }}
                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleFilterCard">
                    <i class="bi bi-chevron-up"></i>
                </button>
            </div>
            <div class="card-body" id="filterBody">
                <form method="GET" action="{{ route('books.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by title, author, or category" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->category }}" {{ request('category') == $cat->category ? 'selected' : '' }}>{{ $cat->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="stock_filter" class="form-label">Stock Filter</label>
                        <select name="stock_filter" id="stock_filter" class="form-select">
                            <option value="">All</option>
                            <option value="available" {{ request('stock_filter') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Low Stock (<5)</option>
                            <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Books') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="toggleCreateForm">
                            + Add Book
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">+ Add Category</a>
                        <a href="{{ route('books.export') }}" class="btn btn-success">Export CSV</a>
                    </div>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Inline Create Form -->
                    <div id="createForm" style="display: none;" class="mb-4 p-3 border rounded bg-light">
                        <h5>Add New Book</h5>
                        <form action="{{ route('books.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title_book" class="form-label"><strong>Title Book:</strong></label>
                                    <input type="text" name="title_book" id="title_book"
                                        class="form-control @error('title_book') is-invalid @enderror" required>
                                    @error('title_book')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="author" class="form-label"><strong>Author:</strong></label>
                                    <input type="text" name="author" id="author"
                                        class="form-control @error('author') is-invalid @enderror" required>
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="publisher" class="form-label"><strong>Publisher:</strong></label>
                                    <input type="text" name="publisher" id="publisher"
                                        class="form-control @error('publisher') is-invalid @enderror" required>
                                    @error('publisher')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label"><strong>Category:</strong></label>
                                    <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->category }}">{{ $cat->category }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label"><strong>Stock:</strong></label>
                                    <input type="number" name="stock" id="stock"
                                        class="form-control @error('stock') is-invalid @enderror" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <button type="button" class="btn btn-secondary" id="cancelCreateForm">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>



                    <table class="table">
                        <thead>
                            <tr id="bulkActionsRow" class="d-none">
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th colspan="7" id="bulkActionsCell">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllHeader">
                                    </div>
                                </th>
                                <th width="80px">No</th>
                                <th>Title Book</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @forelse ($books as $book)
                                <tr class="{{ $book->stock < 5 ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input book-checkbox" type="checkbox" value="{{ $book->id }}">
                                        </div>
                                    </td>
                                    <td width="80px">{{ ++$i }}</td>
                                    <td>{{ $book->title_book }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->publisher }}</td>
                                    <td>{{ $book->category }}</td>
                                    <td class="stock-cell" data-book-id="{{ $book->id }}" style="cursor: pointer; position: relative;">
                                        {{ $book->stock }}
                                        <div class="stock-popover" id="stockPopover{{ $book->id }}" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 10px; z-index: 1000; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                            <div class="mb-2">
                                                <strong>Add Stock</strong>
                                            </div>
                                            <form action="{{ route('books.addStock', $book->id) }}" method="POST" class="d-inline mb-3">
                                                @csrf
                                                <input type="number" name="additional_stock" class="form-control form-control-sm mb-2" placeholder="Enter amount" min="1" required style="width: 100px;">
                                                <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                            </form>

                                            <div class="mb-2">
                                                <strong>Reduce Stock</strong>
                                            </div>
                                            <form action="{{ route('books.reduceStock', $book->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="number" name="reduce_stock" class="form-control form-control-sm mb-2" placeholder="Enter amount" min="1" max="{{ $book->stock }}" required style="width: 100px;">
                                                <button type="submit" class="btn btn-danger btn-sm">Reduce</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('books.show', $book->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('books.edit', $book->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this book?')" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi bi-trash"></i> Delete</button>
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

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            @if ($books->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->previousPageUrl() }}">Previous</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $books->lastPage(); $page++)
                                <li class="page-item {{ $page == $books->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $books->url($page) }}">{{ $page }}</a>
                                </li>
                            @endfor

                            @if ($books->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->nextPageUrl() }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle filter card functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggleFilterBtn = document.getElementById('toggleFilterCard');
        const filterBody = document.getElementById('filterBody');

        if (toggleFilterBtn) {
            toggleFilterBtn.addEventListener('click', function() {
                if (filterBody.style.display === 'none' || filterBody.style.display === '') {
                    filterBody.style.display = 'block';
                    toggleFilterBtn.innerHTML = '<i class="bi bi-chevron-up"></i>';
                } else {
                    filterBody.style.display = 'none';
                    toggleFilterBtn.innerHTML = '<i class="bi bi-chevron-down"></i>';
                }
            });
        }
    });

    // Toggle create form functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleCreateForm');
        const createForm = document.getElementById('createForm');
        const cancelButton = document.getElementById('cancelCreateForm');

        toggleButton.addEventListener('click', function() {
            if (createForm.style.display === 'none' || createForm.style.display === '') {
                createForm.style.display = 'block';
                toggleButton.textContent = '- Hide Add Book';
            } else {
                createForm.style.display = 'none';
                toggleButton.textContent = '+ Add Book';
            }
        });

        cancelButton.addEventListener('click', function() {
            createForm.style.display = 'none';
            toggleButton.textContent = '+ Add Book';
        });
    });

    // Stock popover functionality
    document.addEventListener('DOMContentLoaded', function() {
        const stockCells = document.querySelectorAll('.stock-cell');

        stockCells.forEach(cell => {
            cell.addEventListener('click', function(e) {
                e.stopPropagation();
                const bookId = this.getAttribute('data-book-id');
                const popover = document.getElementById('stockPopover' + bookId);

                // Hide all other popovers
                document.querySelectorAll('.stock-popover').forEach(p => {
                    if (p !== popover) p.style.display = 'none';
                });

                // Toggle current popover
                if (popover.style.display === 'none' || popover.style.display === '') {
                    popover.style.display = 'block';
                } else {
                    popover.style.display = 'none';
                }
            });
        });

        // Prevent hiding popover when clicking inside it
        document.querySelectorAll('.stock-popover').forEach(popover => {
            popover.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Hide popovers when clicking outside
        document.addEventListener('click', function(e) {
            // Don't hide if clicking inside a popover or on a stock cell
            if (!e.target.closest('.stock-popover') && !e.target.closest('.stock-cell')) {
                document.querySelectorAll('.stock-popover').forEach(p => {
                    p.style.display = 'none';
                });
            }
        });

        // Hide popovers when create form is shown
        const createForm = document.getElementById('createForm');
        if (createForm) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        if (createForm.style.display === 'block') {
                            document.querySelectorAll('.stock-popover').forEach(p => {
                                p.style.display = 'none';
                            });
                        }
                    }
                });
            });
            observer.observe(createForm, { attributes: true });
        }

        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
        const bookCheckboxes = document.querySelectorAll('.book-checkbox');
        const bulkActionsRow = document.getElementById('bulkActionsRow');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
            const count = checkedBoxes.length;

            if (count > 0) {
                bulkActionsRow.classList.remove('d-none');
            } else {
                bulkActionsRow.classList.add('d-none');
            }
        }

        selectAllCheckbox.addEventListener('change', function() {
            bookCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            selectAllHeaderCheckbox.checked = this.checked;
            updateBulkActions();
        });

        selectAllHeaderCheckbox.addEventListener('change', function() {
            bookCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            selectAllCheckbox.checked = this.checked;
            updateBulkActions();
        });

        bookCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
                const allChecked = checkedBoxes.length === bookCheckboxes.length;
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
            const checkedBoxes = document.querySelectorAll('.book-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Please select at least one book to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected books?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("books.bulkDelete") }}';

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

                // Book IDs
                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'book_ids[]';
                    input.value = checkbox.value;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    });


</script>
@endpush
