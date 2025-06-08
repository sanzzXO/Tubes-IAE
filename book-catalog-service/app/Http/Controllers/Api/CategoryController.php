<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $books = $category->books()->paginate(12);
        return view('categories.show', compact('category', 'books'));
    }

    public function create()
    {
        return view('categories.create');
    }

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

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

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

    public function destroy(Category $category)
    {
        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')
                           ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku!');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}
