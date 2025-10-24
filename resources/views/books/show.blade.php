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
                        <tr>
                            <th>Created By</th>
                            <td>{{ $book->creator ? $book->creator->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{ $book->updater ? $book->updater->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $book->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $book->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <!-- Log Changes Section -->
                    <div class="mt-4">
                        <h5>Log Changes</h5>
                        <div class="mb-3">
                            <label for="logFilter" class="form-label">Filter Logs:</label>
                            <select id="logFilter" class="form-select">
                                <option value="all">All Changes</option>
                                <option value="updates">Update History</option>
                                <option value="stock">Stock Changes</option>
                                <option value="borrows">Borrowing History</option>
                            </select>
                        </div>
                        <div id="logContent">
                            <!-- Update Logs -->
                            <div class="log-section" data-type="updates">
                                <h6>Update History</h6>
                                @if($book->audits && $book->audits->count() > 0)
                                    <ul class="list-group">
                                        @foreach($book->audits->sortByDesc('created_at') as $audit)
                                            <li class="list-group-item">
                                                <strong>{{ $audit->user ? $audit->user->name : 'System' }}</strong> updated the book
                                                <small class="text-muted">{{ $audit->created_at->diffForHumans() }}</small>
                                                @if($audit->old_values || $audit->new_values)
                                                    <br><em>Changes: {{ json_encode(array_diff_assoc($audit->new_values, $audit->old_values)) }}</em>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No update history recorded.</p>
                                @endif
                            </div>

                            <!-- Stock Logs -->
                            <div class="log-section" data-type="stock">
                                <h6>Stock Changes</h6>
                                @if($book->stockLogs->count() > 0)
                                    <ul class="list-group">
                                        @foreach($book->stockLogs->sortByDesc('created_at') as $log)
                                            <li class="list-group-item">
                                                @if($log->action === 'borrowed')
                                                    <strong>{{ $log->user->name }}</strong> lent books to {{ $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first() ? ($book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow ? $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow->name_borrower : ($book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->user ? $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->user->name : 'N/A')) : 'N/A' }}
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                    <br><em>Remaining stock: {{ $log->new_stock }} of {{ $log->previous_stock }}</em>
                                                    <br><em>Status: Lending books</em>
                                                    <br><em>Book borrowed by {{ $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first() ? ($book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow ? $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow->name_borrower : ($book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->user ? $book->borrows->where('created_at', '>=', $log->created_at)->where('created_at', '<=', $log->created_at->copy()->addSecond())->first()->user->name : 'N/A')) : 'N/A' }}</em>
                                                @elseif($log->action === 'returned')
                                                    <strong>{{ $log->user->name }}</strong> received book return from {{ $book->borrows->where('updated_at', '>=', $log->created_at)->where('updated_at', '<=', $log->created_at->copy()->addSecond())->first() ? ($book->borrows->where('updated_at', '>=', $log->created_at)->where('updated_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow ? $book->borrows->where('updated_at', '>=', $log->created_at)->where('updated_at', '<=', $log->created_at->copy()->addSecond())->first()->dataBorrow->name_borrower : ($book->borrows->where('updated_at', '>=', $log->created_at)->where('updated_at', '<=', $log->created_at->copy()->addSecond())->first()->user ? $book->borrows->where('updated_at', '>=', $log->created_at)->where('updated_at', '<=', $log->created_at->copy()->addSecond())->first()->user->name : 'N/A')) : 'N/A' }}
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                    <br><em>Remaining stock: {{ $log->new_stock }} of {{ $log->previous_stock }}</em>
                                                    <br><em>Status: Book returned</em>
                                                @else
                                                    <strong>{{ $log->user->name }}</strong> {{ $log->action }} {{ $log->change_amount }} stock
                                                    ({{ $log->previous_stock }} → {{ $log->new_stock }})
                                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                    @if($log->notes)
                                                        <br><em>{{ $log->notes }}</em>
                                                    @endif
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No stock changes recorded.</p>
                                @endif
                            </div>

                            <!-- Borrow Logs -->
                            <div class="log-section" data-type="borrows">
                                <h6>Borrowing History</h6>
                                @if($book->borrows->count() > 0)
                                    <ul class="list-group">
                                        @foreach($book->borrows->sortByDesc('created_at') as $borrow)
                                            <li class="list-group-item">
                                                <strong>{{ $borrow->dataBorrow ? $borrow->dataBorrow->name_borrower : ($borrow->user ? $borrow->user->name : 'N/A') }}</strong> borrowed
                                                <small class="text-muted">{{ $borrow->created_at->diffForHumans() }}</small>
                                                @if($borrow->status === 'returned')
                                                    <br><em>Returned: {{ $borrow->updated_at->format('d M Y H:i') }}</em>
                                                @else
                                                    <br><em>Status: {{ $borrow->status }}</em>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No borrowing history.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logFilter = document.getElementById('logFilter');
        const logSections = document.querySelectorAll('.log-section');

        logFilter.addEventListener('change', function() {
            const selectedValue = this.value;

            logSections.forEach(section => {
                if (selectedValue === 'all' || section.getAttribute('data-type') === selectedValue) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
