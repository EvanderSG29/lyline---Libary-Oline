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

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock level
        if ($request->filled('stock_filter')) {
            switch ($request->stock_filter) {
                case 'low':
                    $query->where('stock', '<', 5);
                    break;
                case 'out':
                    $query->where('stock', '=', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
            }
        }

        // Search by title, author, or category
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_book', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

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
        return redirect()->route('books.index')->with('success', 'Buku berhasil disimpan!');
    }

    public function show(Book $book)
    {
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
        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function addStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'additional_stock' => 'required|integer|min:1',
        ]);

        $previousStock = $book->stock;
        $book->increment('stock', $validated['additional_stock']);

        // Log the stock change
        \App\Models\StockLog::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'previous_stock' => $previousStock,
            'new_stock' => $book->fresh()->stock,
            'change_amount' => $validated['additional_stock'],
            'action' => 'added',
            'notes' => 'Stock added via admin panel',
        ]);

        return redirect()->route('books.index')->with('success', 'Stock berhasil ditambahkan!');
    }

    public function exportCsv()
    {
        $books = Book::all();

        $filename = 'books_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($books) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, ['ID', 'Title', 'Author', 'Publisher', 'Category', 'Stock', 'Created At']);

            // CSV data
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->title_book,
                    $book->author,
                    $book->publisher,
                    $book->category,
                    $book->stock,
                    $book->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function reduceStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'reduce_stock' => 'required|integer|min:1',
        ]);

        if ($validated['reduce_stock'] > $book->stock) {
            return redirect()->route('books.index')->with('error', 'Cannot reduce stock below 0!');
        }

        $previousStock = $book->stock;
        $book->decrement('stock', $validated['reduce_stock']);

        // Log the stock change
        \App\Models\StockLog::create([
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'previous_stock' => $previousStock,
            'new_stock' => $book->fresh()->stock,
            'change_amount' => $validated['reduce_stock'],
            'action' => 'reduced',
            'notes' => 'Stock reduced via admin panel',
        ]);

        return redirect()->route('books.index')->with('success', 'Stock berhasil dikurangi!');
    }

    public function bulkUpdateStock(Request $request)
    {
        $validated = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
            'additional_stock' => 'required|integer|min:1',
        ]);

        $books = Book::whereIn('id', $validated['book_ids'])->get();
        $updatedCount = 0;

        foreach ($books as $book) {
            $previousStock = $book->stock;
            $book->increment('stock', $validated['additional_stock']);

            // Log the stock change
            \App\Models\StockLog::create([
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'previous_stock' => $previousStock,
                'new_stock' => $book->fresh()->stock,
                'change_amount' => $validated['additional_stock'],
                'action' => 'added',
                'notes' => 'Bulk stock update',
            ]);

            $updatedCount++;
        }

        return redirect()->route('books.index')->with('success', "Stock berhasil ditambahkan ke {$updatedCount} buku!");
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
        ]);

        $books = Book::whereIn('id', $validated['book_ids'])->get();
        $deletedCount = 0;

        foreach ($books as $book) {
            $book->delete();
            $deletedCount++;
        }

        return redirect()->route('books.index')->with('success', "{$deletedCount} buku berhasil dihapus!");
    }
}
