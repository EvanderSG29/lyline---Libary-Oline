@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>{{ __('Borrow Details') }}</h5>
                        <a href="{{ route('borrows.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Borrower Name</th>
                            <td>{{ $borrow->dataBorrow->name_borrower }}</td>
                        </tr>
                        <tr>
                            <th>Book Title</th>
                            <td>{{ $borrow->book->title_book }}</td>
                        </tr>
                        <tr>
                            <th>Borrow Date</th>
                            <td>{{ $borrow->borrow_date }}</td>
                        </tr>
                        <tr>
                            <th>Return Date</th>
                            <td>{{ $borrow->return_date ?? 'Not returned' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $borrow->status }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
