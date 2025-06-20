<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BorrowingController extends Controller    
{
    // Get all borrowings
    public function getAllBorrowings()
    {
        $borrowings = Borrowing::all();
        $result = $borrowings->map(function ($b) {
            $bookResponse = \Illuminate\Support\Facades\Http::get('http://localhost:8001/api/books/' . $b->book_id);
            $title = $bookResponse->successful() ? ($bookResponse->json()['data']['title'] ?? null) : null;
            return [
                'id' => $b->id,
                'book_id' => $b->book_id,
                'user_id' => $b->user_id,
                'is_returned' => $b->is_returned,
                'created_at' => $b->created_at,
                'updated_at' => $b->updated_at,
                'book_title' => $title,
            ];
        });
        return response()->json(['data' => $result]);
    }

    // Create a new borrowing
    public function createBorrowing(Request $request)
    {
        $request->validate([
            'book_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        $bookId = $request->book_id;
        $userId = $request->user_id;

        // Cek ketersediaan buku di book-catalog-service
        $bookResponse = Http::get('http://localhost:8001/api/books/' . $bookId);
        if (!$bookResponse->successful()) {
            return response()->json([
                'message' => 'Gagal cek ketersediaan buku',
                'error' => 'Book catalog service unavailable'
            ], 503);
        }
        $bookData = $bookResponse->json();
        if (!isset($bookData['data']) || !isset($bookData['data']['available'])) {
            return response()->json([
                'message' => 'Gagal cek ketersediaan buku',
                'error' => 'Invalid response from book catalog service'
            ], 502);
        }
        if ($bookData['data']['available'] < 1) {
            return response()->json([
                'message' => 'Buku tidak tersedia untuk dipinjam',
                'error' => 'Book is not available'
            ], 400);
        }
        // Cek apakah buku sudah dipinjam dan belum dikembalikan
        $existing = Borrowing::where('book_id', $bookId)->where('is_returned', false)->first();
        if ($existing) {
            return response()->json([
                'message' => 'Buku sudah dipinjam',
                'error' => 'Book already borrowed',
                'borrowing_id' => $existing->id
            ], 400);
        }
        $borrowing = Borrowing::create([
            'book_id' => $bookId,
            'user_id' => $userId,
            'is_returned' => false,
        ]);
        return response()->json([
            'message' => 'Peminjaman berhasil',
            'borrowing' => $borrowing,
            'book_title' => $bookData['data']['title'] ?? null
        ], 201);
    }

    // Return a book
    public function returnBook($borrowingId)
    {
        $borrowing = Borrowing::findOrFail($borrowingId);
        if ($borrowing->is_returned) {
            return response()->json([
                'message' => 'Buku sudah dikembalikan',
                'borrowing' => $borrowing
            ]);
        }
        $borrowing->is_returned = true;
        $borrowing->save();
        return response()->json([
            'message' => 'Buku berhasil dikembalikan',
            'borrowing' => $borrowing
        ]);
    }

    // Check if book is currently borrowed
    public function checkBookStatus(Request $request)
    {
        $bookId = $request->book_id;
        $borrowing = Borrowing::where('book_id', $bookId)->where('is_returned', false)->first();
        return response()->json([
            'is_borrowed' => !is_null($borrowing),
            'borrowing_id' => $borrowing?->id
        ]);
    }

    // Get borrowing details with book info
    public function getBorrowingDetails($borrowingId)
    {
        $borrowing = Borrowing::findOrFail($borrowingId);
        $bookResponse = Http::get('http://localhost:8001/api/books/' . $borrowing->book_id);
        $bookDetails = $bookResponse->successful() ? ($bookResponse->json()['data'] ?? null) : null;
        return response()->json([
            'borrowing' => $borrowing,
            'book' => $bookDetails,
            'error' => $bookDetails ? null : 'Failed to fetch book details'
        ]);
    }

    // Get all currently borrowed books
    public function getBorrowedBooks()
    {
        $borrowed = Borrowing::where('is_returned', false)->get();
        $result = $borrowed->map(function ($b) {
            $bookResponse = \Illuminate\Support\Facades\Http::get('http://localhost:8001/api/books/' . $b->book_id);
            $title = $bookResponse->successful() ? ($bookResponse->json()['data']['title'] ?? null) : null;
            return [
                'book_id' => $b->book_id,
                'borrowing_id' => $b->id,
                'user_id' => $b->user_id,
                'borrowed_at' => $b->created_at,
                'book_title' => $title,
            ];
        });
        return response()->json([
            'data' => $result,
            'count' => $result->count()
        ]);
    }
} 