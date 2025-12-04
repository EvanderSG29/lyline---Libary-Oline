<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use App\Http\Requests\BorrowStoreRequest;
use App\Http\Requests\BorrowUpdateRequest;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $borrows = Borrow::with(['user', 'book'])->paginate(10);
        $users = User::all();
        $books = Book::where('stock', '>', 0)->get();

        return view('borrows.index', compact('borrows', 'users', 'books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::all();

        return view('borrows.create', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BorrowStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = 'borrowed';

        $book = Book::findOrFail($validated['book_id']);
        $previousStock = $book->stock;
        $book->decrement('stock');

        // Log the stock change
        \App\Models\StockLog::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'previous_stock' => $previousStock,
            'new_stock' => $book->fresh()->stock,
            'change_amount' => -1,
            'action' => 'borrowed',
            'notes' => 'Book borrowed',
        ]);

        Borrow::create($validated);

        return redirect()->route('borrows.index')->with('success', 'Borrow added successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Borrow $borrow)
    // {
    //     $borrow->load(['user', 'book']);

    //     return view('borrows.show', compact('borrow'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrow $borrow)
    {
        $books = Book::all();
        $users = User::all();

        return view('borrows.edit', compact('borrow', 'books', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BorrowUpdateRequest $request, Borrow $borrow)
    {
        $validated = $request->validated();
        $oldStatus = $borrow->status;
        $newStatus = $validated['status'];

        if ($oldStatus === 'borrowed' && $newStatus === 'returned') {
            $previousStock = $borrow->book->stock;
            $borrow->book->increment('stock');

            // Log the stock change
            \App\Models\StockLog::create([
                'book_id' => $borrow->book->id,
                'user_id' => auth()->id(),
                'previous_stock' => $previousStock,
                'new_stock' => $borrow->book->fresh()->stock,
                'change_amount' => 1,
                'action' => 'returned',
                'notes' => 'Book returned',
            ]);
        } elseif ($oldStatus === 'returned' && $newStatus === 'borrowed') {
            $previousStock = $borrow->book->stock;
            $borrow->book->decrement('stock');
        }

        $borrow->update($validated);

        return redirect()->route('borrows.index')->with('success', 'Borrow updated successfully.');
    }

    /**
     * Update the status of a borrow record.
     */
    public function updateStatus(Request $request, Borrow $borrow)
    {
        $request->validate([
            'status' => 'required|in:borrowed,returned',
        ]);

        $oldStatus = $borrow->status;
        $newStatus = $request->status;

        if ($oldStatus === 'borrowed' && $newStatus === 'returned') {
            $previousStock = $borrow->book->stock;
            $borrow->book->increment('stock');


        } elseif ($oldStatus === 'returned' && $newStatus === 'borrowed') {
            $previousStock = $borrow->book->stock;
            $borrow->book->decrement('stock');


        }

        $borrow->update(['status' => $newStatus]);

        // Reload the model with relations for the frontend
        $borrow->load(['user', 'book']);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.', 'borrow' => $borrow]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        if ($borrow->status === 'borrowed') {
            $borrow->book->increment('stock');
        }

        $borrow->delete();

        return redirect()->route('borrows.index')->with('success', 'Borrow deleted successfully.');
    }
}
