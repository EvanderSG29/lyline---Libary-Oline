<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Book::query();



        // Paginate the results
        $perPage = $request->get('per_page', 10);
        $books = $query->paginate($perPage);
        $categories = \App\Models\Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_book' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book successfully added!');
    }

    public function show(Book $book)
    {
        $book->load(['stockLogs.user']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = \App\Models\Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title_book' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);
        $validated['updated_by'] = auth()->id();
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book successfully updated!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book successfully deleted!');
    }

    public function addStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'additional_stock' => 'required|integer|min:1',
        ]);

        $previousStock = $book->stock;
        $book->increment('stock', $validated['additional_stock']);


        return redirect()->route('books.index')->with('success', 'Stock successfully added!');
    }

}
