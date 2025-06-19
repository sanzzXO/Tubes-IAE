<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    // Menampilkan semua review
    public function index(Request $request): JsonResponse
    {
        // Filter berdasarkan buku atau user jika ada
        $query = Review::query();
        
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Pengurutan dan pagination
        $reviews = $query->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        // Menambahkan informasi user dan buku pada tiap review
        foreach ($reviews as $review) {
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();
        }
        
        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'total' => $reviews->total()
        ]);
    }

    // Menampilkan detail review
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

    // Membuat review baru
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $bookId = $request->book_id;
            $userId = $request->user_id;

            // Validasi apakah buku ada
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
            
            $review = Review::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            // Ambil info user dan book untuk response
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditambahkan',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating review: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah review'
            ], 500);
        }
    }

    // Mengubah review
    public function update(UpdateReviewRequest $request, $id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            
            // Verifikasi user yang mengupdate adalah pemilik review
            if ($review->user_id !== $request->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengupdate review ini'
                ], 403);
            }
            
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            // Ambil info user dan book untuk response
            $review->user = $review->getUserInfo();
            $review->book = $review->getBookInfo();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil diupdate',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update review'
            ], 500);
        }
    }

    // Menghapus review
    public function destroy($id): JsonResponse
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review'
            ], 500);
        }
    }

    // Mendapatkan semua review untuk buku tertentu
    public function getByBook($bookId): JsonResponse
    {
        try {
            // Validasi apakah buku ada
            if (!$this->bookExists($bookId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }
            
            // Ambil semua review untuk buku ini
            $reviews = Review::where('book_id', $bookId)
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
            // Tambahkan info user ke setiap review
            foreach ($reviews as $review) {
                $review->user = $review->getUserInfo();
            }
            
            return response()->json([
                'success' => true,
                'data' => $reviews
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data review'
            ], 500);
        }
    }

    // Helper method - cek apakah buku ada
    protected function bookExists($bookId): bool
    {
        try {
            $response = Http::get(config('services.book_catalog_service.url') . "/api/books/{$bookId}");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}