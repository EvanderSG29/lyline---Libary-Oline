@extends('layouts.main')

@section('content')
@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
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
                                <option value="approaching" {{ request('status') == 'approaching' ? 'selected' : '' }}>Approaching Deadline</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                                    <input type="date" name="borrow_date" class="form-control" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" readonly required>
                                </div>
                                <div class="col-md-3">
                                    <label for="return_date" class="form-label"><strong>Return Date:</strong></label>
                                    <input type="date" name="return_date" class="form-control" required>
                                </div>

                                <div class="col-md-12 mt-3" id="timeInputs" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="borrowed_at" class="form-label"><strong>Borrowed at:</strong></label>
                                            <input type="time" name="borrowed_at" class="form-control" value="09:00">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="returned_at" class="form-label"><strong>Returned at:</strong></label>
                                            <input type="time" name="returned_at" class="form-control" value="14:00">
                                        </div>
                                    </div>
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
                                <th>Note</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @forelse($borrows as $borrow)
                                <tr class="status-{{ $borrow->status_color }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $borrow->status_details }}">
                                    <td><input type="checkbox" class="borrow-checkbox" value="{{ $borrow->id }}"></td>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d') }}</td>
                                    <td>{{ $borrow->user->name }}</td>
                                    <td>{{ $borrow->book->title_book }}</td>
                                    <td>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d') : 'Not returned' }}</td>
                                    <td class="status-column">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-{{ $borrow->status_icon }}" aria-hidden="true"></i>
                                                {{ ucfirst($borrow->status) }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item status-option" href="#" data-borrow-id="{{ $borrow->id }}" data-status="borrowed">Borrowed</a></li>
                                                <li><a class="dropdown-item status-option" href="#" data-borrow-id="{{ $borrow->id }}" data-status="returned">Returned</a></li>
                                            </ul>
                                        </div>
                                        <span class="visually-hidden">{{ $borrow->status_details }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted fst-italic">
                                            @if($borrow->status == 'borrowed' && $borrow->return_date && $borrow->return_date < now())
                                                Past the loan period
                                            @elseif($borrow->status == 'returned')
                                                Book has been returned
                                            @else
                                                Still within loan period
                                            @endif
                                        </small>
                                    </td>
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
                                    <td colspan="11" class="text-center">No data available</td>
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
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

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

    // Show/hide time inputs based on borrow and return dates
    const borrowDateInput = document.querySelector('input[name="borrow_date"]');
    const returnDateInput = document.querySelector('input[name="return_date"]');
    const timeInputs = document.getElementById('timeInputs');

    function checkDates() {
        if (borrowDateInput.value && returnDateInput.value && borrowDateInput.value === returnDateInput.value) {
            timeInputs.style.display = 'block';
        } else {
            timeInputs.style.display = 'none';
        }
    }

    // Check on input for real-time validation
    borrowDateInput.addEventListener('input', checkDates);
    returnDateInput.addEventListener('input', checkDates);

    // Also check on change for compatibility
    borrowDateInput.addEventListener('change', checkDates);
    returnDateInput.addEventListener('change', checkDates);

    // Initial check in case dates are pre-filled
    checkDates();

    // Handle status change via AJAX
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('status-option')) {
            e.preventDefault();
            const borrowId = e.target.getAttribute('data-borrow-id');
            const newStatus = e.target.getAttribute('data-status');

            fetch(`/borrows/${borrowId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to update colors and status
                    location.reload();
                } else {
                    alert('Error updating status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
            });
        }
    });
</script>
@endpush
