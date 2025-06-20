<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffBookController extends Controller
{
    private $apiUrl = 'http://localhost:8001/api';

    public function index()
    {
        $response = Http::get($this->apiUrl . '/books');
        $books = $response->json()['data'] ?? [];
        return view('staff.books.index', compact('books'));
    }

    public function create()
    {
        $catResponse = Http::get($this->apiUrl . '/categories');
        $categories = $catResponse->json()['data'] ?? [];
        return view('staff.books.form', compact('categories'));
    }

    public function store(Request $request)
    {
        Http::post($this->apiUrl . '/books', $request->all());
        return redirect('/staff/books')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bookResponse = Http::get($this->apiUrl . "/books/{$id}");
        $book = $bookResponse->json()['data'] ?? null;
        $catResponse = Http::get($this->apiUrl . '/categories');
        $categories = $catResponse->json()['data'] ?? [];
        return view('staff.books.form', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        Http::put($this->apiUrl . "/books/{$id}", $request->all());
        return redirect('/staff/books')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        Http::delete($this->apiUrl . "/books/{$id}");
        return redirect('/staff/books')->with('success', 'Buku berhasil dihapus');
    }
} 