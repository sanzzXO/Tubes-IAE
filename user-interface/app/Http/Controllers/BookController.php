<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get books from book-catalog-service
            $booksResponse = Http::timeout(30)->get('http://localhost:8001/api/books', [
                'search' => $request->search,
                'category_id' => $request->category_id
            ]);
            
            // Get categories for filter dropdown
            $categoriesResponse = Http::timeout(30)->get('http://localhost:8001/api/categories');
            
            $books = [];
            $categories = [];
            
            if ($booksResponse->successful()) {
                $responseData = $booksResponse->json();
                $books = $responseData['data'] ?? [];
            }
            
            if ($categoriesResponse->successful()) {
                $categoriesData = $categoriesResponse->json();
                $categories = $categoriesData['data'] ?? [];
            }
            
            return view('books.index', compact('books', 'categories'));
            
        } catch (\Exception $e) {
            return view('books.index', [
                'books' => [],
                'categories' => []
            ])->with('error', 'Gagal memuat katalog buku: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::timeout(30)->get("http://localhost:8001/api/books/{$id}");
            
            if ($response->successful()) {
                $responseData = $response->json();
                $book = $responseData['data'] ?? null;
                
                if ($book) {
                    return view('books.show', compact('book'));
                }
            }
            
            return redirect('/books')->with('error', 'Buku tidak ditemukan');
            
        } catch (\Exception $e) {
            return redirect('/books')->with('error', 'Gagal memuat detail buku: ' . $e->getMessage());
        }
    }
}