<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\DataBorrow;
use App\Http\Requests\BorrowStoreRequest;
use App\Http\Requests\BorrowUpdateRequest;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrow::with(['dataBorrow', 'book'])->paginate(10);

        return view('borrows.index', compact('borrows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $databorrows = DataBorrow::all();

        return view('borrows.create', compact('books', 'databorrows'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BorrowStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = 'borrowed';

        $book = Book::findOrFail($validated['book_id']);
        $book->decrement('stock');

        Borrow::create($validated);

        return redirect()->route('borrows.index')->with('success', 'Borrow added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        $borrow->load(['dataBorrow', 'book']);

        return view('borrows.show', compact('borrow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrow $borrow)
    {
        $books = Book::all();
        $databorrows = DataBorrow::all();

        return view('borrows.edit', compact('borrow', 'books', 'databorrows'));
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
            $borrow->book->increment('stock');
        } elseif ($oldStatus === 'returned' && $newStatus === 'borrowed') {
            $borrow->book->decrement('stock');
        }

        $borrow->update($validated);

        return redirect()->route('borrows.index')->with('success', 'Borrow updated successfully.');
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
