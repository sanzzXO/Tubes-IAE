<?php
// app/Http/Controllers/Api/ReviewController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Mendapatkan semua review untuk buku tertentu
     */
    public function getBookReviews(Request $request, $bookId): JsonResponse
    {
        try {
            $cacheKey = "book_reviews_{$bookId}_" . md5(serialize($request->all()));
            
            $reviews = Cache::remember($cacheKey, 300, function () use ($bookId, $request) {
                $query = Review::where('book_id', $bookId)
                              ->approved()
                              ->orderBy('created_at', 'desc');

                // Filter berdasarkan rating jika ada
                if ($request->has('rating')) {
                    $query->where('rating', $request->rating);
                }

                // Pagination
                $perPage = $request->get('per_page', 10);
                $reviews = $query->paginate($perPage);

                // Ambil informasi user dan book untuk setiap review
                foreach ($reviews->items() as $review) {
                    $review->user = $review->getUserInfo();
                    $review->book = $review->getBookInfo();
                }

                return $reviews;
            });

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting book reviews: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil review buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menambah review baru
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $bookId = $request->book_id;
            $userId = $request->user_id; // Ambil user_id dari request

            // Validasi apakah buku exists
            if (!$this->bookExists($bookId)) {
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

    /**
     * Update review
     */
    public function update(UpdateReviewRequest $request, $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $userId = $request->user_id; // Ambil user_id dari request

            if ($review->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengupdate review ini'
                ], 403);
            }

            DB::beginTransaction();
            
            $review->update($request->validated());
            // Reset approval setelah edit
            $review->is_approved = false;
            $review->approved_at = null;
            $review->approved_by = null;
            $review->save();

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

    /**
     * Hapus review
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $userId = $request->user_id; // Ambil user_id dari request

            if ($review->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus review ini'
                ], 403);
            }

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
                'message' => 'Gagal hapus review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve review
     */
    public function approve(Request $request, $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $approvedBy = $request->user_id; // Ambil user_id dari request

            DB::beginTransaction();
            
            $review->update([
                'is_approved' => true,
                'approved_at' => now(),
                'approved_by' => $approvedBy
            ]);

            // Clear cache
            $this->clearBookReviewCache($review->book_id);

            DB::commit();

            // Ambil info user dan book untuk response
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil disetujui',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan statistik review untuk buku
     */
    public function getBookStats($bookId): JsonResponse
    {
        try {
            $cacheKey = "book_stats_{$bookId}";
            
            $stats = Cache::remember($cacheKey, 300, function () use ($bookId) {
                $reviews = Review::where('book_id', $bookId)
                               ->approved()
                               ->get();

                $totalReviews = $reviews->count();
                $averageRating = $reviews->avg('rating');
                $ratingDistribution = $reviews->groupBy('rating')
                                            ->map(function ($group) {
                                                return $group->count();
                                            });

                return [
                    'total_reviews' => $totalReviews,
                    'average_rating' => round($averageRating, 1),
                    'rating_distribution' => $ratingDistribution
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting book stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik buku',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan review yang pending approval
     */
    public function getPendingReviews(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            
            $reviews = Review::pending()
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);

            // Ambil info user dan book untuk setiap review
            foreach ($reviews->items() as $review) {
                $review->user = $review->getUserInfo();
                $review->book = $review->getBookInfo();
            }

            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting pending reviews: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil review pending',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validasi apakah buku exists di book catalog service
     */
    private function bookExists($bookId)
    {
        try {
            $response = Http::timeout(5)
                          ->get(config('services.book_catalog_service.url') . "/api/books/{$bookId}");
            
            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Failed to check book existence: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear cache untuk review buku
     */
    private function clearBookReviewCache($bookId)
    {
        Cache::forget("book_reviews_{$bookId}_" . md5(serialize([])));
        Cache::forget("book_stats_{$bookId}");
    }
}