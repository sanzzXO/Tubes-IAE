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
        $user = session('user');
        if ($user && isset($user['role']) && $user['role'] === 'staff') {
            return redirect('/staff/dashboard');
        }
        // Ambil data user dari auth-service
        $response = Http::get($this->apiUrl . "/users");
        $users = $response->json()['data'] ?? [];
        $userData = null;
        foreach ($users as $u) {
            if (isset($u['id']) && $u['id'] == $userId) {
                $userData = $u;
                break;
            }
        }
        // Fallback jika tidak ketemu
        if (!$userData) {
            $userData = [
                'name' => '-',
                'email' => '-',
                'role' => '-',
            ];
        }
        return view('dashboard', ['user' => $userData]);
    }
} 
