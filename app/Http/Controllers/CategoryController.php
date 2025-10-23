<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $query = Category::query();

        // Search by category name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('category', 'like', "%{$search}%");
        }

        // Sort
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate
        $categories = $query->paginate(10);

        return view('books.categories.index', compact('categories'))
            ->with('i', ($categories->currentPage() - 1) * $categories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        Category::create($data);
        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('books.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('books.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        $category->update($data);
        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request)
    {
        $categoryIds = $request->input('category_ids', []);

        if (empty($categoryIds)) {
            return redirect()->route('categories.index')
                ->with('error', 'No categories selected for deletion.');
        }

        Category::whereIn('id', $categoryIds)->delete();

        return redirect()->route('categories.index')
            ->with('success', count($categoryIds) . ' categories deleted successfully.');
    }
}
