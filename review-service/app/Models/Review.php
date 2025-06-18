<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'comment'
        // Removed is_approved, approved_at, approved_by
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Method untuk mendapatkan info user dari auth service
    public function getUserInfo()
    {
        try {
            $response = Http::timeout(5)
                          ->get(config('services.auth_service.url') . "/api/users/{$this->user_id}");
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch user info from auth service: " . $e->getMessage());
        }
        
        return null;
    }

    // Method untuk mendapatkan info book dari book catalog service
    public function getBookInfo()
    {
        try {
            $response = Http::timeout(5)
                          ->get(config('services.book_catalog_service.url') . "/api/books/{$this->book_id}");
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error("Failed to fetch book info from catalog service: " . $e->getMessage());
        }
        
        return null;
    }
}