<?php

namespace App\Http\Controllers;

use App\Models\DataBorrow;
use Illuminate\Http\Request;
use App\Http\Requests\DataBorrowStoreRequest;
use App\Http\Requests\DataBorrowUpdateRequest;

class DataBorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DataBorrow::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Search by name or identifier
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('identifier', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate
        $databorrows = $query->paginate(10);

        return view('borrows.databorrows.index', compact('databorrows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('borrows.databorrows.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DataBorrowStoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->input('type') !== 'User') {
            $validated['class'] = $validated['class'] ?? null; // Atau 'N/A' jika Anda ingin string
        }

        DataBorrow::create($validated);
        return redirect()->route('databorrows.index')->with('success', 'Borrower added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DataBorrow $databorrow)
    {
        return view('borrows.databorrows.show', compact('databorrow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataBorrow $databorrow)
    {
        return view('borrows.databorrows.edit', compact('databorrow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DataBorrowUpdateRequest $request, DataBorrow $databorrow)
    {
        $validated = $request->validated();

        if ($request->input('type') !== 'User') {
            $validated['class'] = $validated['class'] ?? null;
        }

        $databorrow->update($validated);
        return redirect()->route('databorrows.index')->with('success', 'Borrower updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataBorrow $databorrow)
    {
        $databorrow->delete();
        return redirect()->route('databorrows.index')->with('success', 'Borrower deleted successfully.');
    }
}
