<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            
            return response()->json([
                'status' => 'success',
                'data' => $categories
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $books = $category->books()->paginate(12);
        return view('categories.show', compact('category', 'books'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')
                           ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku!');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}
