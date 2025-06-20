<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffReviewController extends Controller
{
    private $apiUrl = 'http://localhost:8003/api';

    public function index()
    {
        $response = Http::get($this->apiUrl . '/reviews');
        $reviews = $response->json()['data'] ?? [];
        return view('staff.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        Http::delete($this->apiUrl . "/reviews/{$id}");
        return redirect('/staff/reviews')->with('success', 'Review berhasil dihapus');
    }
} 