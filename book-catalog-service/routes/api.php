<?php

use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\CategoryApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Books API Routes
Route::prefix('books')->group(function () {
    Route::get('/', [BookApiController::class, 'index']);
    Route::get('/{id}', [BookApiController::class, 'show']);
    Route::post('/', [BookApiController::class, 'store']);
    Route::put('/{id}', [BookApiController::class, 'update']);
    Route::delete('/{id}', [BookApiController::class, 'destroy']);
    Route::get('/search/{query}', [BookApiController::class, 'search']);
});

// Categories API Routes
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index']);
    Route::get('/{id}', [CategoryApiController::class, 'show']);
    Route::post('/', [CategoryApiController::class, 'store']);
    Route::put('/{id}', [CategoryApiController::class, 'update']);
    Route::delete('/{id}', [CategoryApiController::class, 'destroy']);
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Book Catalog Service is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});