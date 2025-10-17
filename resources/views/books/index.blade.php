@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Books') }}</div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between mb-3 gap-2">

                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            + Add Book
                        </button>
                        
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">+ Add Category</a>
                    </div>

                    <!-- Modal Create -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Book</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('books.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <label for="title_book" class="form-label"><strong>Title Book:</strong></label>
                                        <input type="text" name="title_book" id="title_book"
                                            class="form-control @error('title_book') is-invalid @enderror" required>
                                        @error('title_book')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <br>
                                        <label for="author" class="form-label"><strong>Author:</strong></label>
                                        <input type="text" name="author" id="author"
                                            class="form-control @error('author') is-invalid @enderror" required>
                                        @error('author')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <br>
                                        <label for="publisher" class="form-label"><strong>Publisher:</strong></label>
                                        <input type="text" name="publisher" id="publisher"
                                            class="form-control @error('publisher') is-invalid @enderror" required>
                                        @error('publisher')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <br>
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
                                        <br>
                                        <label for="stock" class="form-label"><strong>Stock:</strong></label>
                                        <input type="number" name="stock" id="stock"
                                            class="form-control @error('stock') is-invalid @enderror" required>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title Book</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $book->title_book }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->publisher }}</td>
                                <td>{{ $book->category }}</td>
                                <td>{{ $book->stock }}</td>
                                    
                                <td>
                                    <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('are you sure to delete?')">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $book->id }}">
                                        Add Stock
                                    </button>


                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $books->links() }}

                </div>
                
                @foreach($books as $book)
                <!-- Modal Add Stock -->
                <div class="modal fade" id="addStockModal{{ $book->id }}" tabindex="-1" aria-labelledby="addStockModalLabel{{ $book->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addStockModalLabel{{ $book->id }}">Add Stock for {{ $book->title_book }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('books.addStock', $book->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="additional_stock" class="form-label"><strong>Additional Stock:</strong></label>
                                        <input type="number" name="additional_stock" id="additional_stock" class="form-control @error('additional_stock') is-invalid @enderror" required>
                                        @error('additional_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Stock</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach



            </div>
        </div>
    </div>
</div>
@endsection