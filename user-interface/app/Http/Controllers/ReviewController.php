<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    private $apiUrl = 'http://localhost:8003/api';

    public function index(Request $request)
    {
        $bookId = $request->input('book_id');
        $params = [];
        if ($bookId) $params['book_id'] = $bookId;
        $response = Http::get($this->apiUrl . '/reviews', $params);
        $body = $response->json();
        if (isset($body['data']) && is_array($body['data'])) {
            $reviews = $body['data'];
        } else {
            $reviews = [];
        }
        return view('reviews.index', compact('reviews', 'bookId'));
    }

    public function create($bookId)
    {
        return view('reviews.create', compact('bookId'));
    }

    public function store(Request $request)
    {
        $userId = session('user_id');
        $response = Http::post($this->apiUrl . '/reviews', [
            'book_id' => $request->input('book_id'),
            'user_id' => $userId,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);
        $body = $response->json();
        $bookId = $request->input('book_id');
        if ($response->successful() && ($body['success'] ?? false)) {
            return redirect('/reviews?book_id=' . $bookId)->with('success', 'Review berhasil ditambahkan!');
        } else {
            $errorMsg = $body['message'] ?? 'Gagal menambah review.';
            return redirect('/reviews/create/' . $bookId)->withErrors(['review' => $errorMsg]);
        }
    }
} 