<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffBorrowingController extends Controller
{
    private $apiUrl = 'http://localhost:8002/api';

    public function index()
    {
        $response = Http::get($this->apiUrl . '/borrowings');
        $borrowings = $response->json()['data'] ?? [];
        return view('staff.borrowings.index', compact('borrowings'));
    }

    public function return($id)
    {
        Http::post($this->apiUrl . "/borrowings/{$id}/return");
        return redirect('/staff/borrowings')->with('success', 'Buku berhasil dikembalikan');
    }
} 