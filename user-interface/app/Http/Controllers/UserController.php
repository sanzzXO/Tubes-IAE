<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    private $apiUrl = 'http://localhost:8000/api';

    public function dashboard(Request $request)
    {
        $userId = session('user_id');
        $response = Http::get($this->apiUrl . "/users/{$userId}");
        $user = $response->json();
        return view('dashboard', compact('user'));
    }
} 