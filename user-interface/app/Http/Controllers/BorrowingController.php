<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BorrowingController extends Controller
{
    private $apiUrl = 'http://localhost:8002/api';

    public function index(Request $request)
    {
        // Asumsi user_id diambil dari session atau token
        $userId = session('user_id');
        $response = Http::get($this->apiUrl . "/borrowings", ['user_id' => $userId]);
        $body = $response->json();
        if (isset($body['data']) && is_array($body['data'])) {
            $borrowings = $body['data'];
        } else {
            $borrowings = [];
        }
        return view('borrowings.index', compact('borrowings'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        $response = Http::post($this->apiUrl . '/borrowings', [
            'book_id' => $request->input('book_id'),
            'user_id' => $userId,
        ]);
        $body = $response->json();
        if ($response->successful() && isset($body['message']) && str_contains(strtolower($body['message']), 'berhasil')) {
            return redirect('/borrowings')->with('success', 'Peminjaman berhasil!');
        } else {
            $errorMsg = $body['message'] ?? 'Gagal meminjam buku.';
            return redirect('/books')->withErrors(['borrow' => $errorMsg]);
        }
    }

    public function return($id)
    {
        $response = Http::post($this->apiUrl . "/borrowings/{$id}/return");
        $body = $response->json();
        if ($response->successful() && isset($body['message']) && str_contains(strtolower($body['message']), 'berhasil')) {
            return redirect('/books')->with('success', 'Buku berhasil dikembalikan!');
        } else {
            $errorMsg = $body['message'] ?? 'Gagal mengembalikan buku.';
            return redirect('/books')->withErrors(['return' => $errorMsg]);
        }
    }
} 