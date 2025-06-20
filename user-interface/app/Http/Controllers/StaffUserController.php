<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffUserController extends Controller
{
    private $apiUrl = 'http://localhost:8000/api';

    public function index()
    {
        $response = Http::get($this->apiUrl . '/users');
        $users = $response->json()['data'] ?? [];
        return view('staff.users.index', compact('users'));
    }

    public function create()
    {
        return view('staff.users.form');
    }

    public function store(Request $request)
    {
        Http::post($this->apiUrl . '/register', $request->all());
        return redirect('/staff/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $response = Http::get($this->apiUrl . "/users/{$id}");
        $user = $response->json()['data'] ?? null;
        return view('staff.users.form', compact('user'));
    }

    public function update(Request $request, $id)
    {
        Http::put($this->apiUrl . "/users/{$id}", $request->all());
        return redirect('/staff/users')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        Http::delete($this->apiUrl . "/users/{$id}");
        return redirect('/staff/users')->with('success', 'User berhasil dihapus');
    }
} 