<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffCategoryController extends Controller
{
    private $apiUrl = 'http://localhost:8001/api';

    public function index()
    {
        $response = Http::get($this->apiUrl . '/categories');
        $categories = $response->json()['data'] ?? [];
        return view('staff.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('staff.categories.form');
    }

    public function store(Request $request)
    {
        Http::post($this->apiUrl . '/categories', $request->all());
        return redirect('/staff/categories')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $response = Http::get($this->apiUrl . "/categories/{$id}");
        $category = $response->json()['data'] ?? null;
        return view('staff.categories.form', compact('category'));
    }

    public function update(Request $request, $id)
    {
        Http::put($this->apiUrl . "/categories/{$id}", $request->all());
        return redirect('/staff/categories')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        Http::delete($this->apiUrl . "/categories/{$id}");
        return redirect('/staff/categories')->with('success', 'Kategori berhasil dihapus');
    }
} 