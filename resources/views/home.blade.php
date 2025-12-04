@extends('layouts.main')

@section('content')
@php
    $user = auth()->user();
@endphp

@if($user->role === App\Enums\UserRole::Admin)
    <!-- Admin Dashboard -->
    <div class="container-fluid">

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('books.create') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Add Book
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('users.create') }}" class="btn btn-success w-100">
                                    <i class="bi bi-person-plus me-2"></i>Add User
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('borrows.index') }}" class="btn btn-warning w-100">
                                    <i class="bi bi-arrow-left-right me-2"></i>Manage Borrows
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('bookings.index') }}" class="btn btn-info w-100">
                                    <i class="bi bi-calendar-check me-2"></i>Manage Bookings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Total Books</h6>
                                <h3 class="mb-0">{{ $stats['total_books'] }}</h3>
                            </div>
                            <i class="bi bi-book-half fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Total Users</h6>
                                <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            </div>
                            <i class="bi bi-people-fill fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Active Borrows</h6>
                                <h3 class="mb-0">{{ $stats['active_borrows'] }}</h3>
                            </div>
                            <i class="bi bi-arrow-left-right fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Due Today</h6>
                                <h3 class="mb-0">{{ $stats['books_due_today'] }}</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle-fill fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Total Bookings</h6>
                                <h3 class="mb-0">{{ $stats['total_bookings'] }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Pending Bookings</h6>
                                <h3 class="mb-0">{{ $stats['pending_bookings'] }}</h3>
                            </div>
                            <i class="bi bi-clock fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activities -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Borrowing Trends (Last 6 Months)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="borrowingChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Recent Activities</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                            @forelse($recentActivities as $activity)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-muted">{{ $activity['created_at']->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $activity['message'] }}</p>
                                    <small class="text-{{ $activity['type'] === 'borrow' ? 'primary' : 'success' }}">
                                        <i class="bi bi-{{ $activity['type'] === 'borrow' ? 'arrow-left-right' : 'calendar-check' }} me-1"></i>
                                        {{ ucfirst($activity['type']) }}
                                    </small>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No recent activities
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

@elseif($user->role === App\Enums\UserRole::Staff)
    <!-- Staff Dashboard -->
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-primary">
                    <i class="bi bi-speedometer2 me-2"></i>Staff Dashboard
                </h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Total Books</h6>
                                <h3 class="mb-0">{{ $stats['total_books'] }}</h3>
                            </div>
                            <i class="bi bi-book-half fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Active Borrows</h6>
                                <h3 class="mb-0">{{ $stats['active_borrows'] }}</h3>
                            </div>
                            <i class="bi bi-arrow-left-right fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Due Today</h6>
                                <h3 class="mb-0">{{ $stats['books_due_today'] }}</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle-fill fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">Pending Bookings</h6>
                                <h3 class="mb-0">{{ $stats['pending_bookings'] }}</h3>
                            </div>
                            <i class="bi bi-clock fs-1 opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Content -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-star me-2"></i>Frequently Borrowed Books</h5>
                    </div>
                    <div class="card-body">
                        @forelse($frequentBooks as $book)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-book-half text-primary fs-4 me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $book->title_book }}</h6>
                                    <small class="text-muted">{{ $book->borrows_count }} borrows</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No borrowing data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Current Borrowers</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            @forelse($currentBorrowers as $borrow)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $borrow->dataBorrow->name_borrower }}</h6>
                                            <p class="mb-1 text-muted">{{ $borrow->book->title_book }}</p>
                                            <small class="text-muted">Due: {{ $borrow->return_date->format('M d, Y') }}</small>
                                        </div>
                                        <span class="badge bg-warning">Borrowed</span>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No active borrows
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Your Recent Transactions</h5>
                    </div>
                    <div class="card-body">
                        @forelse($staffTransactions as $transaction)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-book-half text-primary fs-4 me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $transaction->book->title_book }}</h6>
                                    <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge bg-{{ $transaction->status === 'returned' ? 'success' : 'warning' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No recent transactions</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@else
    <!-- User Dashboard -->
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-4 text-primary">
                    <i class="bi bi-house-door me-2"></i>My Dashboard
                </h1>
            </div>
        </div>

        <!-- Reading Progress -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-book-half fs-1 mb-3 opacity-75"></i>
                        <h4>{{ $readingProgress['current_books'] }}</h4>
                        <p class="mb-0">Books Currently Reading</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle fs-1 mb-3 opacity-75"></i>
                        <h4>{{ $readingProgress['completed_this_month'] }}</h4>
                        <p class="mb-0">Completed This Month</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-trophy fs-1 mb-3 opacity-75"></i>
                        <h4>{{ $readingProgress['total_borrowed'] }}</h4>
                        <p class="mb-0">Total Books Borrowed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Borrows -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-bookmark-star me-2"></i>Books Currently On Loan</h5>
                    </div>
                    <div class="card-body">
                        @forelse($userBorrows as $borrow)
                            <div class="card mb-3 border-{{ $borrow->return_date->isPast() ? 'danger' : 'success' }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="card-title">{{ $borrow->book->title_book }}</h5>
                                            <p class="card-text text-muted mb-2">Borrowed on {{ $borrow->borrow_date->format('M d, Y') }}</p>
                                            <p class="mb-0">
                                                <strong>Return Date:</strong>
                                                <span class="badge bg-{{ $borrow->return_date->isPast() ? 'danger' : 'warning' }}">
                                                    {{ $borrow->return_date->format('M d, Y') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            @if($borrow->return_date->isPast())
                                                <span class="badge bg-danger fs-6 p-2">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>Overdue
                                                </span>
                                            @else
                                                <span class="badge bg-success fs-6 p-2">
                                                    <i class="bi bi-check-circle-fill me-1"></i>On Time
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-book-half text-muted fs-1 mb-3"></i>
                                <h5 class="text-muted">No books currently on loan</h5>
                                <p class="text-muted">Start borrowing books to see them here!</p>
                                <a href="{{ route('books.index') }}" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i>Browse Books
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings and History -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Recent Bookings</h5>
                    </div>
                    <div class="card-body">
                        @forelse($userBookings as $booking)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-book-half text-primary fs-4 me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $booking->book->title_book }}</h6>
                                    <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                                </div>
                                <span class="badge bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No recent bookings</p>
                        @endforelse
                        <div class="text-center mt-3">
                            <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Book a Book
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Borrowing History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            @forelse($borrowHistory as $borrow)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $borrow->book->title_book }}</h6>
                                            <small class="text-muted">
                                                Borrowed: {{ $borrow->borrow_date->format('M d, Y') }}
                                                @if($borrow->status === 'returned')
                                                    | Returned: {{ $borrow->updated_at->format('M d, Y') }}
                                                @endif
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $borrow->status === 'returned' ? 'success' : 'warning' }}">
                                            {{ ucfirst($borrow->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    No borrowing history
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

