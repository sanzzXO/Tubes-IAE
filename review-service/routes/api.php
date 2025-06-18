<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewController;

// Get reviews for a book
Route::middleware('throttle:api')->group(function () {
    // Get all reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    
    // Get specific review
    Route::get('/reviews/{id}', [ReviewController::class, 'show']);
    
    // Create a new review
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    // Get reviews by book
    Route::get('/books/{bookId}/reviews', [ReviewController::class, 'getByBook']);
    
    // Admin routes
    Route::put('/reviews/{id}/approve', [ReviewController::class, 'approve']);
});