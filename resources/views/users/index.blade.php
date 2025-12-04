@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Data Users</h4>
                    <div class="d-flex gap-2">
                        {{-- <a href="{{ route('users.export', request()->query()) }}" class="btn btn-success btn-sm"><i class="bi bi-download"></i> Export CSV</a> --}}
                        <a id="addUserLink" href="{{ route('users.create', ['type' => 'user']) }}" class="btn btn-primary btn-sm">Add User</a>
                    </div>
                </div>
                <div class="card-body">


                    <!-- Table -->
                    <div class="table-responsive">
                    <table class="table">
                        <thead>

                            <tr>
                                <th></th>
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

