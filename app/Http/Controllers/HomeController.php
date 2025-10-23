<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Booking;
use App\Models\DataBorrow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === \App\Enums\UserRole::Admin) {
            // Admin Dashboard Data
            $stats = [
                'total_books' => Book::count(),
                'total_users' => User::count(),
                'active_borrows' => Borrow::where('status', 'borrowed')->count(),
                'books_due_today' => Borrow::where('status', 'borrowed')
                    ->whereDate('return_date', Carbon::today())
                    ->count(),
                'total_bookings' => Booking::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
            ];

            // Recent activities
            $recentActivities = collect();

            // Recent borrows
            $recentBorrows = Borrow::with(['dataBorrow', 'book'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function($borrow) {
                    return [
                        'type' => 'borrow',
                        'message' => "{$borrow->dataBorrow->name_borrower} borrowed '{$borrow->book->title_book}'",
                        'created_at' => $borrow->created_at
                    ];
                });

            // Recent bookings
            $recentBookings = Booking::with(['user', 'book'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function($booking) {
                    return [
                        'type' => 'booking',
                        'message' => "{$booking->user->name} booked '{$booking->book->title_book}'",
                        'created_at' => $booking->created_at
                    ];
                });

            $recentActivities = $recentBorrows->concat($recentBookings)
                ->sortByDesc('created_at')
                ->take(10);

            // Monthly borrowing trend (last 6 months)
            $monthlyBorrows = Borrow::select(
                    DB::raw('strftime(\'%Y\', created_at) as year'),
                    DB::raw('strftime(\'%m\', created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(function($item) {
                    return [
                        'month' => Carbon::createFromFormat('Y-m', $item->year . '-' . $item->month)->format('M Y'),
                        'count' => $item->count
                    ];
                });

            return view('home', compact('stats', 'recentActivities', 'monthlyBorrows'));

        } elseif ($user->role === \App\Enums\UserRole::Staff) {
            // Staff Dashboard Data
            $stats = [
                'total_books' => Book::count(),
                'active_borrows' => Borrow::where('status', 'borrowed')->count(),
                'books_due_today' => Borrow::where('status', 'borrowed')
                    ->whereDate('return_date', Carbon::today())
                    ->count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
            ];

            // Frequently borrowed books
            $frequentBooks = Book::withCount(['borrows' => function($query) {
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                }])
                ->orderBy('borrows_count', 'desc')
                ->take(5)
                ->get();

            // Current borrowers
            $currentBorrowers = Borrow::with(['dataBorrow', 'book'])
                ->where('status', 'borrowed')
                ->latest('borrow_date')
                ->take(10)
                ->get();

            // Staff's recent transactions
            $staffTransactions = Borrow::where('user_id', $user->id)
                ->with('book')
                ->latest()
                ->take(5)
                ->get();

            return view('home', compact('stats', 'frequentBooks', 'currentBorrowers', 'staffTransactions'));

        } else {
            // User Dashboard Data
            $userBorrows = Borrow::where('user_id', $user->id)
                ->with('book')
                ->where('status', 'borrowed')
                ->get();

            $userBookings = Booking::where('user_id', $user->id)
                ->with('book')
                ->latest()
                ->take(5)
                ->get();

            $borrowHistory = Borrow::where('user_id', $user->id)
                ->with('book')
                ->latest()
                ->take(10)
                ->get();

            // Reading progress (mock data - you can implement actual progress tracking)
            $readingProgress = [
                'current_books' => $userBorrows->count(),
                'completed_this_month' => Borrow::where('user_id', $user->id)
                    ->where('status', 'returned')
                    ->whereMonth('updated_at', Carbon::now()->month)
                    ->count(),
                'total_borrowed' => Borrow::where('user_id', $user->id)->count(),
            ];

            return view('home', compact('userBorrows', 'userBookings', 'borrowHistory', 'readingProgress'));
        }
    }
}
