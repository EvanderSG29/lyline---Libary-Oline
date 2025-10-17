@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>{{ __('Book Details') }}</h5>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Title Book</th>
                            <td>{{ $book->title_book }}</td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td>{{ $book->author }}</td>
                        </tr>
                        <tr>
                            <th>Publisher</th>
                            <td>{{ $book->publisher }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $book->category }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>{{ $book->stock }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
