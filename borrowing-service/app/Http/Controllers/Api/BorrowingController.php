<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Services\BookService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    protected $bookService;
    protected $userService;

    public function __construct(BookService $bookService, UserService $userService)
    {
        $this->bookService = $bookService;
        $this->userService = $userService;
    }

    /**
     * Menampilkan daftar peminjaman
     */
    public function index(Request $request): JsonResponse
    {
        $query = Borrowing::query();

        // Filter berdasarkan status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan buku
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Filter overdue
        if ($request->has('overdue') && $request->overdue == 'true') {
            $query->overdue();
        }

        $borrowings = $query->orderBy('created_at', 'desc')
                           ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $borrowings,
            'message' => 'Data peminjaman berhasil diambil'
        ]);
    }

    /**
     * Membuat peminjaman baru
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'book_id' => 'required|string',
            'borrowed_date' => 'required|date',
            'loan_period_days' => 'integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            // Cek ketersediaan buku
            $book = $this->bookService->getBook($request->book_id);
            if (!$book || $book['available_copies'] <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak tersedia untuk dipinjam'
                ], 400);
            }

            // Cek data user
            $user = $this->userService->getUser($request->user_id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Cek apakah user sudah meminjam buku yang sama
            $existingBorrow = Borrowing::where('user_id', $request->user_id)
                                     ->where('book_id', $request->book_id)
                                     ->where('status', 'borrowed')
                                     ->first();

            if ($existingBorrow) {
                return response()->json([
                    'success' => false,
                    'message' => 'User sudah meminjam buku ini'
                ], 400);
            }

            // Cek batas maksimal peminjaman user
            $activeLoans = Borrowing::where('user_id', $request->user_id)
                                  ->where('status', 'borrowed')
                                  ->count();

            if ($activeLoans >= 5) { // Maksimal 5 buku
                return response()->json([
                    'success' => false,
                    'message' => 'User sudah mencapai batas maksimal peminjaman'
                ], 400);
            }

            $loanPeriod = $request->get('loan_period_days', 14); // Default 14 hari
            $borrowedDate = Carbon::parse($request->borrowed_date);
            $dueDate = $borrowedDate->addDays($loanPeriod);

            // Buat data peminjaman
            $borrowing = Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'isbn' => $book['isbn'] ?? null,
                'book_title' => $book['title'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'borrowed_date' => $borrowedDate,
                'due_date' => $dueDate,
                'status' => 'borrowed',
                'notes' => $request->notes
            ]);

            // Update stok buku di Book Service
            $this->bookService->decreaseStock($request->book_id, 1);

            // Clear cache
            Cache::forget("user_borrowings_{$request->user_id}");
            Cache::forget("book_borrowings_{$request->book_id}");

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Buku berhasil dipinjam'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show($id): JsonResponse
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        // Update status overdue jika perlu
        $borrowing->updateOverdueStatus();

        return response()->json([
            'success' => true,
            'data' => $borrowing,
            'message' => 'Detail peminjaman berhasil diambil'
        ]);
    }

    /**
     * Mengembalikan buku
     */
    public function returnBook(Request $request, $id): JsonResponse
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($borrowing->status === 'returned') {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah dikembalikan sebelumnya'
            ], 400);
        }

        try {
            $returnDate = $request->get('returned_date', now());
            $returnDate = Carbon::parse($returnDate);

            // Hitung denda jika terlambat
            $fineAmount = 0;
            if ($returnDate->gt($borrowing->due_date)) {
                $overdueDays = $returnDate->diffInDays($borrowing->due_date);
                $fineAmount = $overdueDays * 1000; // Rp 1000 per hari
            }

            // Update data peminjaman
            $borrowing->update([
                'returned_date' => $returnDate,
                'status' => 'returned',
                'fine_amount' => $fineAmount,
                'notes' => $request->get('return_notes', $borrowing->notes)
            ]);

            // Update stok buku di Book Service
            $this->bookService->increaseStock($borrowing->book_id, 1);

            // Clear cache
            Cache::forget("user_borrowings_{$borrowing->user_id}");
            Cache::forget("book_borrowings_{$borrowing->book_id}");

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => 'Buku berhasil dikembalikan',
                'fine_amount' => $fineAmount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perpanjang masa peminjaman
     */
    public function extend(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'extend_days' => 'required|integer|min:1|max:14'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($borrowing->status !== 'borrowed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya buku yang sedang dipinjam yang bisa diperpanjang'
            ], 400);
        }

        // Cek apakah sudah terlambat
        if ($borrowing->due_date < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat memperpanjang buku yang sudah terlambat'
            ], 400);
        }

        try {
            $extendDays = $request->extend_days;
            $newDueDate = Carbon::parse($borrowing->due_date)->addDays($extendDays);

            $borrowing->update([
                'due_date' => $newDueDate,
                'notes' => $borrowing->notes . " | Diperpanjang {$extendDays} hari pada " . now()->format('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'data' => $borrowing,
                'message' => "Masa peminjaman berhasil diperpanjang {$extendDays} hari"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan history peminjaman user
     */
    public function userHistory($userId): JsonResponse
    {
        $cacheKey = "user_borrowings_{$userId}";
        
        $borrowings = Cache::remember($cacheKey, 3600, function () use ($userId) {
            return Borrowing::where('user_id', $userId)
                           ->orderBy('created_at', 'desc')
                           ->get();
        });

        return response()->json([
            'success' => true,
            'data' => $borrowings,
            'message' => 'History peminjaman user berhasil diambil'
        ]);
    }

    /**
     * Mendapatkan statistik peminjaman
     */
    public function statistics(): JsonResponse
    {
        $stats = Cache::remember('borrowing_statistics', 1800, function () {
            return [
                'total_borrowings' => Borrowing::count(),
                'active_borrowings' => Borrowing::where('status', 'borrowed')->count(),
                'overdue_borrowings' => Borrowing::overdue()->count(),
                'total_returned' => Borrowing::where('status', 'returned')->count(),
                'total_fines' => Borrowing::sum('fine_amount'),
                'avg_loan_period' => Borrowing::whereNotNull('returned_date')
                    ->selectRaw('AVG(DATEDIFF(returned_date, borrowed_date)) as avg_days')
                    ->first()->avg_days ?? 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistik peminjaman berhasil diambil'
        ]);
    }
}