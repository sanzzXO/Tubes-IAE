<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $response = Http::post('http://localhost:8000/api/login', [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);
        $data = $response->json();
        if ($response->successful() && isset($data['user']) && isset($data['token'])) {
            // Simpan user_id dan token ke session
            Session::put('user_id', $data['user']['id']);
            Session::put('token', $data['token']);
            Session::put('user', $data['user']); // Simpan data user untuk pengecekan role

            if ($data['user']['role'] === 'staff') {
                return redirect('/staff/dashboard');
            }
            
            return redirect('/dashboard');
        } else {
            $errorMsg = $data['message'] ?? 'Email atau password salah';
            return redirect('/login')->withErrors(['login' => $errorMsg]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/')->with('success', 'Berhasil logout.');
    }
} 