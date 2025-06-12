<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'books' => Book::count(),
            'available' => Book::sum('available'),
            'categories' => Category::count(),
            'borrowed' => Book::sum('stock') - Book::sum('available')
        ];

        $recentBooks = Book::with('category')
                          ->latest()
                          ->take(6)
                          ->get();

        $popularCategories = Category::withCount('books')
                                   ->orderBy('books_count', 'desc')
                                   ->take(4)
                                   ->get();

        return view('home', compact('stats', 'recentBooks', 'popularCategories'));
    }
}