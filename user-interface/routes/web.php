<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', function () {
    return view('home');
});

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Book routes (public access)
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

// Protected routes
Route::middleware('auth.session')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::get('/borrowings/create/{book_id}', [BorrowingController::class, 'create']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'return']);
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/reviews/create/{book_id}', [ReviewController::class, 'create']);
    Route::post('/reviews', [ReviewController::class, 'store']);
});

