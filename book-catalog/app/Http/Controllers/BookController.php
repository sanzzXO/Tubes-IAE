<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('books.index', ['books' => $books]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json($book, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Book not found'], 404);
        }
    }
}
