<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    // URL Book Catalog Service
    private $apiUrl = 'http://localhost:8001/api';

    public function index(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');
        $params = [];
        if ($query) $params['q'] = $query;
        if ($category) $params['category'] = $category;
        $response = Http::get($this->apiUrl . '/books', $params);
        $body = $response->json();
        if (isset($body['data']) && is_array($body['data'])) {
            $books = $body['data'];
        } elseif (isset($body['books']) && is_array($body['books'])) {
            $books = $body['books'];
        } elseif (is_array($body) && isset($body[0]['title'])) {
            $books = $body;
        } else {
            $books = [];
        }
        // Ambil semua borrowing user yang belum dikembalikan
        $userId = session('user_id');
        $userBorrowings = [];
        if ($userId) {
            $borrowResponse = Http::get('http://localhost:8002/api/borrowings', [
                'user_id' => $userId
            ])->json();
            if (isset($borrowResponse['data']) && is_array($borrowResponse['data'])) {
                foreach ($borrowResponse['data'] as $borrowing) {
                    if (isset($borrowing['is_returned']) && $borrowing['is_returned'] == false) {
                        $userBorrowings[$borrowing['book_id']] = $borrowing['id'];
                    }
                }
            }
        }
        foreach ($books as &$book) {
            $book['is_borrowed_by_user'] = false;
            $book['borrowing_id'] = null;
            if (isset($userBorrowings[$book['id']])) {
                $book['is_borrowed_by_user'] = true;
                $book['borrowing_id'] = $userBorrowings[$book['id']];
            }
        }
        unset($book);
        return view('books.index', compact('books', 'query', 'category'));
    }

    public function show($id)
    {
        $response = Http::get($this->apiUrl . "/books/{$id}");
        $book = $response->json();
        // Ambil review dari Review Service
        $reviewResponse = Http::get('http://localhost:8003/api/reviews', ['book_id' => $id]);
        $book['reviews'] = $reviewResponse->json();
        return view('books.show', compact('book'));
    }
} 