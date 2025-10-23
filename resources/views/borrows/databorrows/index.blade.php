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
                            <input type="text" name="search" class="form-control" placeholder="Search by name or identifier..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="type" class="form-control">
                                <option value="">All Types</option>
                                <option value="Student" {{ request('type') == 'Student' ? 'selected' : '' }}>Student</option>
                                <option value="Teacher" {{ request('type') == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="Staff" {{ request('type') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Guest" {{ request('type') == 'Guest' ? 'selected' : '' }}>Guest</option>
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
                        <input type="text" id="searchInputStudent" class="form-control" placeholder="Client-side search students...">
                        <button class="btn btn-outline-secondary d-none" type="button" id="clearButtonStudent">
                            <i class="bi bi-x"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" id="searchButtonStudent">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" id="searchInputOther" class="form-control" placeholder="Client-side search others...">
                        <button class="btn btn-outline-secondary d-none" type="button" id="clearButtonOther">
                            <i class="bi bi-x"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" id="searchButtonOther">
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
                    {{ __('Book borrowers') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleFilterCard">
                            <i class="bi bi-funnel-fill fs-6"></i> Filters
                        </button>
                        {{-- <button type="button" class="btn btn-success" id="toggleCreateForm">
                            + Add User
                        </button> --}}
                        <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Create DataBorrow Form -->
                    <div id="createForm" class="mb-4 d-none">
                        <form action="{{ route('databorrows.store') }}" method="POST" id="databorrowForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="name_borrower" class="form-control" placeholder="Enter borrower name" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="Student">Student</option>
                                        <option value="Teacher">Teacher</option>
                                        <option value="Staff">Staff</option>
                                        <option value="Guest">Guest</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <button type="button" class="btn btn-secondary" id="cancelCreate">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @php
                        $students = $databorrows->where('type', 'User');
                        $others = $databorrows->where('type', '!=', 'User');
                        $i = 0;
                        $j = 0;
                    @endphp

                    <h4>Student</h4>
                    <table class="table">
                        <thead>
                            <tr id="bulkActionsRowStudent" class="d-none">
                                <th><input type="checkbox" id="selectAllStudent"></th>
                                <th colspan="7" id="bulkActionsCellStudent">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtnStudent">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" id="selectAllHeaderStudent"></th>
                                <th width="80px">No</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $databorrow)
                                <tr>
                                    <td><input type="checkbox" class="student-checkbox" value="{{ $databorrow->id }}"></td>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $databorrow->name_borrower }}</td>
                                    <td>{{ $databorrow->class }}</td>
                                    <td>{{ $databorrow->formatted_phone_number }}</td>
                                    <td>{{ $databorrow->gender }}</td>
                                    <td>{{ $databorrow->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $databorrow->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('databorrows.show', $databorrow->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('databorrows.edit', $databorrow->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('databorrows.destroy', $databorrow->id) }}" method="POST" style="display:inline;">
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
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <h4>Other</h4>
                    <table class="table">
                        <thead>
                            <tr id="bulkActionsRowOther" class="d-none">
                                <th><input type="checkbox" id="selectAllOther"></th>
                                <th colspan="7" id="bulkActionsCellOther">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtnOther">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th><input type="checkbox" id="selectAllHeaderOther"></th>
                                <th width="80px">No</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($others as $databorrow)
                                <tr>
                                    <td><input type="checkbox" class="other-checkbox" value="{{ $databorrow->id }}"></td>
                                    <td>{{ ++$j }}</td>
                                    <td>{{ $databorrow->name_borrower }}</td>
                                    <td>{{ $databorrow->position }}</td>
                                    <td>{{ $databorrow->formatted_phone_number }}</td>
                                    <td>{{ $databorrow->gender }}</td>
                                    <td>{{ $databorrow->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $databorrow->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('databorrows.show', $databorrow->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('databorrows.edit', $databorrow->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('databorrows.destroy', $databorrow->id) }}" method="POST" style="display:inline;">
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
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $databorrows->links() }}

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

    // Auto-hide success alert after 8 seconds
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 8000);

    const searchInputStudent = document.getElementById('searchInputStudent');
    const clearButtonStudent = document.getElementById('clearButtonStudent');
    const searchButtonStudent = document.getElementById('searchButtonStudent');

    const searchInputOther = document.getElementById('searchInputOther');
    const clearButtonOther = document.getElementById('clearButtonOther');
    const searchButtonOther = document.getElementById('searchButtonOther');

    // Toggle clear button visibility for students
    searchInputStudent.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            clearButtonStudent.classList.remove('d-none');
        } else {
            clearButtonStudent.classList.add('d-none');
        }
    });

    // Clear input on clear button click for students
    clearButtonStudent.addEventListener('click', function() {
        searchInputStudent.value = '';
        clearButtonStudent.classList.add('d-none');
        // Reset table display
        const rows = document.querySelectorAll('table:first-of-type tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });

    // Search functionality for students on button click
    searchButtonStudent.addEventListener('click', function() {
        const searchTerm = searchInputStudent.value.toLowerCase();
        const rows = document.querySelectorAll('table:first-of-type tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Toggle clear button visibility for others
    searchInputOther.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            clearButtonOther.classList.remove('d-none');
        } else {
            clearButtonOther.classList.add('d-none');
        }
    });

    // Clear input on clear button click for others
    clearButtonOther.addEventListener('click', function() {
        searchInputOther.value = '';
        clearButtonOther.classList.add('d-none');
        // Reset table display
        const rows = document.querySelectorAll('table:last-of-type tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    });

    // Search functionality for others on button click
    searchButtonOther.addEventListener('click', function() {
        const searchTerm = searchInputOther.value.toLowerCase();
        const rows = document.querySelectorAll('table:last-of-type tbody tr');

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
    const databorrowForm = document.getElementById('databorrowForm');

    toggleCreateFormBtn.addEventListener('click', function() {
        if (createForm.classList.contains('d-none')) {
            createForm.classList.remove('d-none');
            toggleCreateFormBtn.textContent = 'Hide Form';
            // Focus on the first input field
            createForm.querySelector('input[name="name_borrower"]').focus();
        } else {
            createForm.classList.add('d-none');
            toggleCreateFormBtn.textContent = '+ Add User';
            // Reset form
            databorrowForm.reset();
        }
    });

    cancelCreateBtn.addEventListener('click', function() {
        createForm.classList.add('d-none');
        toggleCreateFormBtn.textContent = '+ Add User';
        databorrowForm.reset();
    });
</script>
@endpush
