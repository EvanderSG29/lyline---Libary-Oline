@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>{{ __('Booking Details') }}</h5>
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="bi bi-hash"></i> ID:</strong> {{ $booking->id }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-person"></i> User:</strong> {{ $booking->user->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="bi bi-book"></i> Book:</strong> {{ $booking->book->title_book }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-info-circle"></i> Status:</strong>
                            <span class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="bi bi-calendar-plus"></i> Created At:</strong> {{ $booking->created_at->format('d M Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong><i class="bi bi-calendar-check"></i> Updated At:</strong> {{ $booking->updated_at->format('d M Y H:i') }}
                        </div>
                    </div>
                    @if(auth()->user()->role === App\Enums\UserRole::Admin || auth()->user()->role === App\Enums\UserRole::Staff)
                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                        </div>
                    @endif

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
                                @if($booking->audits->count() > 0)
                                    <ul class="list-group">
                                        @foreach($booking->audits->sortByDesc('created_at') as $audit)
                                            <li class="list-group-item">
                                                <strong>{{ $audit->user ? $audit->user->name : 'System' }}</strong> updated the booking
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
                                <h6>Stock Changes for "{{ $booking->book->title_book }}"</h6>
                                @if($booking->book->stockLogs->count() > 0)
                                    <ul class="list-group">
                                        @foreach($booking->book->stockLogs->sortByDesc('created_at') as $log)
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
                                <h6>Borrowing History for "{{ $booking->book->title_book }}"</h6>
                                @if($booking->book->borrows->count() > 0)
                                    <ul class="list-group">
                                        @foreach($booking->book->borrows->sortByDesc('created_at') as $borrow)
                                            <li class="list-group-item">
                                                <strong>{{ $borrow->dataBorrow->name_borrower }}</strong> borrowed
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
