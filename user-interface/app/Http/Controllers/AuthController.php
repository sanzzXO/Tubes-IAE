<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Debug: tampilkan data yang dikirim
            echo "🔍 LOGIN DEBUG:\n";
            echo "Email: " . $request->email . "\n";
            echo "Password length: " . strlen($request->password) . "\n";
            echo "Auth service URL: http://localhost:8000/api/login\n\n";

            $response = Http::timeout(30)->post('http://localhost:8000/api/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            // Debug: tampilkan response
            echo "Response Status: " . $response->status() . "\n";
            echo "Response Body: " . $response->body() . "\n\n";

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === true) {
                    // Store user session
                    session([
                        'user_id' => $data['user']['id'],
                        'user_name' => $data['user']['name'],
                        'user_email' => $data['user']['email'],
                        'user_role' => $data['user']['role'],
                        'token' => $data['token']
                    ]);

                    echo "✅ Login successful, redirecting to dashboard...\n";
                    if ($data['user']['role'] === 'staff') {
                        return redirect('/staff/dashboard');
                    }
                    return redirect('/dashboard');
                }
            }

            echo "❌ Login failed\n";
            return back()->with('error', 'Email atau password salah');

        } catch (\Exception $e) {
            echo "💥 Exception: " . $e->getMessage() . "\n";
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $response = Http::timeout(30)->post('http://localhost:8000/api/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
                }
            }

            $errorData = $response->json();
            return back()->with('error', $errorData['message'] ?? 'Registrasi gagal');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}