<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\AuthService;
use App\Services\BookCatalogService;

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
        $authService = app(AuthService::class);
        return $authService->getUser($this->user_id);
    }

    // Method untuk mendapatkan info book dari book catalog service
    public function getBookInfo()
    {
        $bookService = app(BookCatalogService::class);
        return $bookService->getBook($this->book_id);
    }
}