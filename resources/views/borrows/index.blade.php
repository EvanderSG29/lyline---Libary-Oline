@extends('layouts.main')

@section('content')
@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Borrow Book') }}
                    <div class="d-flex gap-2">
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
 
                            <tr>

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

