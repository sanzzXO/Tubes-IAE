<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    private $bookApiUrl = 'http://localhost:8001/api';
    private $authApiUrl = 'http://localhost:8000/api';

    public function dashboard(Request $request)
    {
        $books = Http::get($this->bookApiUrl . '/books')->json();
        $categories = Http::get($this->bookApiUrl . '/categories')->json();
        $users = Http::get($this->authApiUrl . '/users')->json();
        return view('staff.dashboard', compact('books', 'categories', 'users'));
    }
} 