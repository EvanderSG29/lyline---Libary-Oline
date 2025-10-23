<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\StockLog;
use App\Http\Requests\BookingStoreRequest;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'book']);

        // For non-admin/staff users, only show their own bookings
        if (Auth::user()->role !== UserRole::Admin && Auth::user()->role !== UserRole::Staff) {
            $query->where('user_id', Auth::id());
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by user name or book title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery->where('title_book', 'like', "%{$search}%");
                });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate
        $bookings = $query->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::all();
        return view('bookings.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingStoreRequest $request)
    {
        Booking::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'book'])->findOrFail($id);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        $books = Book::all();
        return view('bookings.edit', compact('booking', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update($request->only(['status']));

        // Send notifications based on status change
        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'approved') {
                $booking->user->notify(new \App\Notifications\BookingApproved($booking));
                $this->createBorrowFromBooking($booking);
            } elseif ($newStatus === 'revise') {
                $booking->user->notify(new \App\Notifications\BookingRevised($booking));
            } elseif ($newStatus === 'rejected') {
                // Optional: Add rejection notification
            }
        }

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Create borrow records from approved booking
     */
    private function createBorrowFromBooking(Booking $booking)
    {
        // Check if book is available
        if ($booking->book->stock <= 0) {
            throw new \Exception('Book is out of stock.');
        }

        // Create Borrow record with booking dates
        $borrow = Borrow::create([
            'user_id' => $booking->user_id,
            'book_id' => $booking->book_id,
            'borrow_date' => $booking->borrow_date,
            'return_date' => $booking->return_date,
            'status' => 'borrowed',
        ]);

        // Decrement book stock
        $previousStock = $booking->book->stock;
        $booking->book->decrement('stock', 1);

        // Log the stock change
        StockLog::create([
            'book_id' => $booking->book_id,
            'user_id' => Auth::id(),
            'previous_stock' => $previousStock,
            'new_stock' => $booking->book->fresh()->stock,
            'change_amount' => -1,
            'action' => 'borrowed',
            'notes' => 'Stock reduced due to booking approval',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
