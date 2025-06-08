<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Books
    Route::apiResource('books', BookController::class);
    Route::patch('books/{id}/stock', [BookController::class, 'updateStock']);
});