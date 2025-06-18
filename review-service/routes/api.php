<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewController;

// Reviews API routes
Route::middleware('throttle:api')->group(function () {
    // Get all reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    
    // Get specific review
    Route::get('/reviews/{id}', [ReviewController::class, 'show']);
    
    // Create a new review
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    // Update a review
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    
    // Delete a review
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
    
    // Get reviews by book
    Route::get('/books/{bookId}/reviews', [ReviewController::class, 'getByBook']);
    
    // Removed approval route
});