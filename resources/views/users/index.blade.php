@extends('layouts.main')

@section('content')
<div class="container">
    <!-- Hidden Filter Card -->
    <div id="filterCard" style="display: none;" class="mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{ __('messages.filters') }}
                <button type="button" class="btn-close" id="closeFilterCard" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <!-- Server-side Filters -->
                <form method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <select name="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="guest" {{ request('role') == 'guest' ? 'selected' : '' }}>Guest</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <select name="active" class="form-select">
                                <option value="">All Status</option>
                                <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
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
                    <h4>Users</h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleFilterCard">
                            <i class="bi bi-funnel-fill fs-6"></i> Filters
                        </button>
                        <a href="{{ route('users.export', request()->query()) }}" class="btn btn-success btn-sm"><i class="bi bi-download"></i> Export CSV</a>
                        <a id="addUserLink" href="{{ route('users.create', ['type' => 'user']) }}" class="btn btn-primary btn-sm">Add User</a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Bulk Actions -->
                    <form id="bulkForm" method="POST" class="mb-3">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label" for="selectAll">
                                        Select All
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="col-md-6 text-end">
                                <button type="submit" formaction="{{ route('users.bulkToggleActive') }}" class="btn btn-warning btn-sm me-2" onclick="setBulkAction('activate')">Activate Selected</button>
                                <button type="submit" formaction="{{ route('users.bulkToggleActive') }}" class="btn btn-secondary btn-sm me-2" onclick="setBulkAction('deactivate')">Deactivate Selected</button>
                                <button type="submit" formaction="{{ route('users.bulkDelete') }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete selected users?')">Delete Selected</button>
                                <input type="hidden" name="active" id="bulkActive">
                            </div> --}}
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                            {{-- <tr id="bulkActionsRow" class="d-none">
                                <th><input type="checkbox" id="selectAll"></th>
                                <th colspan="8" id="bulkActionsCell">
                                    <button type="button" class="btn btn-warning btn-sm me-2" id="bulkActivateBtn">
                                        <i class="bi bi-check-circle"></i> Activate Selected
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm me-2" id="bulkDeactivateBtn">
                                        <i class="bi bi-x-circle"></i> Deactivate Selected
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr> --}}
                            <tr>
                                <th><input type="checkbox" id="selectAllHeader"></th>
                                <th width="80px">No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $i = 0; @endphp
                            @forelse ($users as $user)
                                <tr>
                                    <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                                    <td>{{ ++$i }}</td>
                                    <td><i class="bi bi-person"></i> {{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->value }}</td>
                                    <td>{{ $user->position ?: '-' }}</td>
                                    <td>
                                        <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical fs-6"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('users.show', $user->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('users.edit', $user->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('users.toggleActive', $user->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item {{ $user->active ? 'text-secondary' : 'text-success' }}" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi {{ $user->active ? 'bi-toggle-off' : 'bi-toggle-on' }}"></i> {{ $user->active ? 'Deactivate' : 'Activate' }}</button>
                                                </form></li>
                                                <li><form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this user?')" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi bi-trash"></i> Delete</button>
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
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @if ($page == $users->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                    @endif
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

    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action helper
    function setBulkAction(action) {
        const activeInput = document.getElementById('bulkActive');
        if (action === 'activate') {
            activeInput.value = '1';
        } else if (action === 'deactivate') {
            activeInput.value = '0';
        }
    }

    // Client-side search functionality
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush
