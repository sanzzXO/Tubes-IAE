<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    /**
     * Display a listing of books
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Book::with('category');

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('author', 'like', '%' . $search . '%')
                      ->orWhere('isbn', 'like', '%' . $search . '%');
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

            // Pagination
            $perPage = $request->get('per_page', 12);
            $books = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $books->items(),
                'pagination' => [
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                    'per_page' => $books->perPage(),
                    'total' => $books->total(),
                    'from' => $books->firstItem(),
                    'to' => $books->lastItem()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve books',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('covers', 'public');
                $data['cover_image'] = $path;
            }

            $data['available'] = $data['stock'];

            $book = Book::create($data);
            $book->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully',
                'data' => $book
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified book
     */
    public function show($id): JsonResponse
    {
        try {
            $book = Book::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Book retrieved successfully',
                'data' => $book
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $book = Book::findOrFail($id);

            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('cover_image')) {
                // Delete old cover image
                if ($book->cover_image) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                $path = $request->file('cover_image')->store('covers', 'public');
                $data['cover_image'] = $path;
            }

            $book->update($data);
            $book->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully',
                'data' => $book
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified book
     */
    public function destroy($id): JsonResponse
    {
        try {
            $book = Book::findOrFail($id);

            // Delete cover image if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search books
     */
    public function search($query): JsonResponse
    {
        try {
            $books = Book::with('category')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('author', 'like', '%' . $query . '%')
                      ->orWhere('isbn', 'like', '%' . $query . '%');
                })
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => $books,
                'count' => $books->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
