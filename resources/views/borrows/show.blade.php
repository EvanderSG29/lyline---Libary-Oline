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
                            <td>{{ $borrow->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Book Title</th>
                            <td>{{ $borrow->book->title_book }}</td>
                        </tr>
                        <tr>
                            <th>Borrow Date</th>
                            <td>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d') }}</td>
                        </tr>
                        @if($borrow->return_date && \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d') === \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d'))
                        <tr>
                            <th>Borrowed at</th>
                            <td>{{ $borrow->borrowed_at ?? 'Not specified' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Return Date</th>
                            <td>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d') : 'Not returned' }}</td>
                        </tr>
                        @if($borrow->return_date && \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d') === \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d'))
                        <tr>
                            <th>Returned at</th>
                            <td>{{ $borrow->returned_at ?? 'Not returned' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td>{{ $borrow->status }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $borrow->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $borrow->updated_at->format('d M Y H:i') }}</td>
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
                                @if($borrow->audits && $borrow->audits->count() > 0)
                                    <ul class="list-group">
                                        @foreach($borrow->audits->sortByDesc('created_at') as $audit)
                                            <li class="list-group-item">
                                                <strong>{{ $audit->user ? $audit->user->name : 'System' }}</strong> updated the borrow record
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

                            <!-- Stock Logs for the Book -->
                            <div class="log-section" data-type="stock">
                                <h6>Stock Changes for "{{ $borrow->book->title_book }}"</h6>
                                @if($borrow->book->stockLogs && $borrow->book->stockLogs->count() > 0)
                                    <ul class="list-group">
                                        @foreach($borrow->book->stockLogs->sortByDesc('created_at') as $log)
                                            <li class="list-group-item">
                                                <strong>{{ $log->user->name }}</strong> {{ $log->action }} {{ $log->change_amount }} stock
                                                ({{ $log->previous_stock }} → {{ $log->new_stock }})
                                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                                @if($log->notes)
                                                    <br><em>{{ $log->notes }}</em>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No stock changes recorded for this book.</p>
                                @endif
                            </div>

                            <!-- Borrow Logs for the Book -->
                            <div class="log-section" data-type="borrows">
                                <h6>Borrowing History for "{{ $borrow->book->title_book }}"</h6>
                                @if($borrow->book->borrows && $borrow->book->borrows->count() > 0)
                                    <ul class="list-group">
                                        @foreach($borrow->book->borrows->sortByDesc('created_at') as $bookBorrow)
                                            <li class="list-group-item {{ $bookBorrow->id === $borrow->id ? 'list-group-item-primary' : '' }}">
                                                <strong>{{ $bookBorrow->user->name }}</strong> borrowed
                                                <small class="text-muted">{{ $bookBorrow->created_at->diffForHumans() }}</small>
                                                @if($bookBorrow->status === 'returned')
                                                    <br><em>Returned: {{ $bookBorrow->updated_at->format('d M Y H:i') }}</em>
                                                @else
                                                    <br><em>Status: {{ $bookBorrow->status }}</em>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No borrowing history for this book.</p>
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
