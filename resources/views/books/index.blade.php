@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Books') }}
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" id="toggleCreateForm">
                            + Add Book
                        </button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">+ Add Category</a>
                        {{-- <a href="{{ route('books.export') }}" class="btn btn-success">Export CSV</a> --}}
                    </div>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Inline Create Form -->
                    <div id="createForm" style="display: none;" class="mb-4 p-3 border rounded bg-light">
                        <h5>Add New Book</h5>
                        <form action="{{ route('books.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title_book" class="form-label"><strong>Title Book:</strong></label>
                                    <input type="text" name="title_book" id="title_book"
                                        class="form-control @error('title_book') is-invalid @enderror" required>
                                    @error('title_book')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="author" class="form-label"><strong>Author:</strong></label>
                                    <input type="text" name="author" id="author"
                                        class="form-control @error('author') is-invalid @enderror" required>
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="publisher" class="form-label"><strong>Publisher:</strong></label>
                                    <input type="text" name="publisher" id="publisher"
                                        class="form-control @error('publisher') is-invalid @enderror" required>
                                    @error('publisher')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label"><strong>Stock:</strong></label>
                                    <input type="number" name="stock" id="stock"
                                        class="form-control @error('stock') is-invalid @enderror" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <button type="button" class="btn btn-secondary" id="cancelCreateForm">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>



                    <table class="table">
                        <thead>
                            <tr id="bulkActionsRow" class="d-none">
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th colspan="7" id="bulkActionsCell">
                                    <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAllHeader">
                                    </div>
                                </th>
                                <th width="80px">No</th>
                                <th>Title Book</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @forelse ($books as $book)
                                <tr class="{{ $book->stock < 5 ? 'table-warning' : '' }}">

                                    <td width="80px">{{ ++$i }}</td>
                                    <td>{{ $book->title_book }}</td>
                                    <td>{{ $book->author }}</td>
                                    <td>{{ $book->publisher }}</td>
                                    <td>{{ $book->category }}</td>
                               
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('books.show', $book->id) }}"><i class="bi bi-eye"></i> Show</a></li>
                                                <li><a class="dropdown-item" href="{{ route('books.edit', $book->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this book?')" style="border: none; background: none; width: 100%; text-align: left; padding: 0.375rem 1.5rem;"><i class="bi bi-trash"></i> Delete</button>
                                                </form></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            @if ($books->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->previousPageUrl() }}">Previous</a>
                                </li>
                            @endif

                            @for ($page = 1; $page <= $books->lastPage(); $page++)
                                <li class="page-item {{ $page == $books->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $books->url($page) }}">{{ $page }}</a>
                                </li>
                            @endfor

                            @if ($books->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $books->nextPageUrl() }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

