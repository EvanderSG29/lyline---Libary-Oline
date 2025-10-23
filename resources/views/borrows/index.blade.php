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
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Search by user name or book title..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                        <div class="col-md-3">
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
                    {{ __('Borrow Book') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleFilterCard">
                            <i class="bi bi-funnel-fill fs-6"></i> Filters
                        </button>
                        <button type="button" class="btn btn-success" id="toggleCreateForm">
                            + Add Borrower
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Manage Users</a>
                    </div>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Create Borrow Form -->
                    <div id="createForm" class="mb-4 p-3 border rounded bg-light">
                        <form action="{{ route('borrows.store') }}" method="POST" id="borrowForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label"><strong>Borrower:</strong></label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">Select Borrower</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="book_id" class="form-label"><strong>Book:</strong></label>
                                    <select name="book_id" class="form-control" required>
                                        <option value="">Select Book</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}">{{ $book->title_book }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="borrow_date" class="form-label"><strong>Borrow Date:</strong></label>
                                    <input type="date" name="borrow_date" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="return_date" class="form-label"><strong>Return Date:</strong></label>
                                    <input type="date" name="return_date" class="form-control" required>
                                </div>

                                <div class="col-md-12 mt-3 d-flex justify-content-end">
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
                                <th colspan="8" id="bulkActionsCell">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" id="selectAllHeader"></th>
                                <th width="80px">No</th>
                                <th>Borrow Date</th>
                                <th>Borrower Name</th>
                                <th>Book Title</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @forelse($borrows as $borrow)
                                <tr>
                                    <td><input type="checkbox" class="borrow-checkbox" value="{{ $borrow->id }}"></td>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $borrow->borrow_date }}</td>
                                    <td>{{ $borrow->user->name }}</td>
                                    <td>{{ $borrow->book->title_book }}</td>
                                    <td>{{ $borrow->return_date ?? 'Not returned' }}</td>
                                    <td>{{ $borrow->status }}</td>
                                    <td>{{ $borrow->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $borrow->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('borrows.show', $borrow->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('borrows.edit', $borrow->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('borrows.destroy', $borrow->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure to delete?')" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi bi-trash"></i> Delete</button>
                                                </form></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $borrows->links() }}

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
    const borrowForm = document.getElementById('borrowForm');

    toggleCreateFormBtn.addEventListener('click', function() {
        if (createForm.classList.contains('d-none')) {
            createForm.classList.remove('d-none');
            toggleCreateFormBtn.textContent = 'Hide Form';
            // Focus on the first select field
            createForm.querySelector('select[name="data_borrow_id"]').focus();
        } else {
            createForm.classList.add('d-none');
            toggleCreateFormBtn.textContent = '+ Add Borrower';
            // Reset form
            borrowForm.reset();
        }
    });

    cancelCreateBtn.addEventListener('click', function() {
        createForm.classList.add('d-none');
        toggleCreateFormBtn.textContent = '+ Add Borrower';
        borrowForm.reset();
    });
</script>
@endpush
