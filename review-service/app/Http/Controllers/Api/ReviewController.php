<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Services\AuthService;
use App\Services\BookCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    protected $authService;
    protected $bookService;

    public function __construct(AuthService $authService, BookCatalogService $bookService)
    {
        $this->authService = $authService;
        $this->bookService = $bookService;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Review::query();
        
        // Filters
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortField, $sortDir);
        
        // Pagination
        $perPage = $request->input('per_page', 10);
        $reviews = $query->paginate($perPage);
        
        // Add user and book info
        foreach ($reviews as $review) {
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();
        }
        
        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'meta' => [
                'total' => $reviews->total(),
                'per_page' => $reviews->perPage(),
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();
            
            return response()->json([
                'success' => true,
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review tidak ditemukan'
            ], 404);
        }
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $bookId = $request->book_id;
            $userId = $request->user_id;

            // Validasi apakah buku exists menggunakan service
            if (!$this->bookService->bookExists($bookId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }

            // Cek apakah user sudah pernah review buku ini
            $existingReview = Review::where('user_id', $userId)
                                   ->where('book_id', $bookId)
                                   ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memberikan review untuk buku ini'
                ], 400);
            }

            DB::beginTransaction();
            
            $reviewData = $request->validated();
            $reviewData['user_id'] = $userId;
            
            $review = Review::create($reviewData);

            // Clear cache
            $this->clearBookReviewCache($bookId);

            DB::commit();

            // Ambil info user dan book untuk response
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditambahkan',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateReviewRequest $request, $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $userId = $request->user_id;

            if ($review->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengupdate review ini'
                ], 403);
            }

            DB::beginTransaction();
            
            $review->update($request->validated());

            // Clear cache
            $this->clearBookReviewCache($review->book_id);

            DB::commit();

            // Ambil info user dan book untuk response
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil diupdate',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            
            DB::beginTransaction();
            
            $bookId = $review->book_id;
            $review->delete();
            
            // Clear cache
            $this->clearBookReviewCache($bookId);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByBook($bookId): JsonResponse
    {
        try {
            // Cache key
            $cacheKey = "book_reviews_{$bookId}";
            
            // Try to get from cache
            if (Cache::has($cacheKey)) {
                return response()->json([
                    'success' => true,
                    'data' => Cache::get($cacheKey),
                    'source' => 'cache'
                ]);
            }
            
            // Validate if book exists using service
            if (!$this->bookService->bookExists($bookId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }
            
            // Get reviews for this book
            $reviews = Review::where('book_id', $bookId)
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
            // Add user info to each review
            foreach ($reviews as $review) {
                $review->user = $review->getUserInfo();
            }
            
            // Store in cache for 30 minutes
            Cache::put($cacheKey, $reviews, now()->addMinutes(30));
            
            return response()->json([
                'success' => true,
                'data' => $reviews,
                'source' => 'database'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting reviews by book: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper methods
    protected function clearBookReviewCache($bookId): void
    {
        Cache::forget("book_reviews_{$bookId}");
    }
}