@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Borrow Book') }}</div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('borrows.create') }}" class="btn btn-success">+ Add Borrower</a>

                        <a href="{{ route('databorrows.index') }}" class="btn btn-secondary">Manage Borrowers</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Borrow Date</th>
                                <th>Borrower Name</th>
                                <th>Book Title</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach($borrows as $borrow)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $borrow->borrow_date }}</td>
                                <td>{{ $borrow->dataBorrow->name_borrower }}</td>
                                <td>{{ $borrow->book->title_book }}</td>
                                <td>{{ $borrow->return_date ?? 'Not returned' }}</td>
                                <td>{{ $borrow->status }}</td>
                                <td>
                                    <a href="{{ route('borrows.show', $borrow->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('borrows.edit', $borrow->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('borrows.destroy', $borrow->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $borrows->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection