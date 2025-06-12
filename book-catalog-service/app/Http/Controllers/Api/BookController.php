<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->has('available_only') && $request->available_only) {
            $query->where('available', '>', 0);
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     */
    public function apiIndex(Request $request)
    {
        $query = Book::with('category');

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->has('available_only') && $request->available_only) {
            $query->available();
        }

        $books = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'publication_year' => 'required|integer|min:1|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $validated['available'] = $validated['stock'];

        Book::create($validated);

        return redirect()->route('books.index')
                        ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function showApi($id)
    {
        $book = Book::with('category')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'publication_year' => 'required|integer|min:1|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        $book->update($validated);

        return redirect()->route('books.index')
                        ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')
                        ->with('success', 'Buku berhasil dihapus!');
    }

    public function updateStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'available' => 'required|integer|min:0|max:' . $request->stock
        ]);

        $book->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'data' => $book
        ]);
    }
}
