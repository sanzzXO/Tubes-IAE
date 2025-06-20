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

Route::prefix('staff')->group(function () {
    Route::get('/dashboard', 'App\\Http\\Controllers\\StaffController@dashboard');
    // Buku
    Route::get('/books', 'App\\Http\\Controllers\\StaffBookController@index');
    Route::get('/books/create', 'App\\Http\\Controllers\\StaffBookController@create');
    Route::post('/books', 'App\\Http\\Controllers\\StaffBookController@store');
    Route::get('/books/{id}/edit', 'App\\Http\\Controllers\\StaffBookController@edit');
    Route::post('/books/{id}/update', 'App\\Http\\Controllers\\StaffBookController@update');
    Route::post('/books/{id}/delete', 'App\\Http\\Controllers\\StaffBookController@destroy');
    // Kategori
    Route::get('/categories', 'App\\Http\\Controllers\\StaffCategoryController@index');
    Route::get('/categories/create', 'App\\Http\\Controllers\\StaffCategoryController@create');
    Route::post('/categories', 'App\\Http\\Controllers\\StaffCategoryController@store');
    Route::get('/categories/{id}/edit', 'App\\Http\\Controllers\\StaffCategoryController@edit');
    Route::post('/categories/{id}/update', 'App\\Http\\Controllers\\StaffCategoryController@update');
    Route::post('/categories/{id}/delete', 'App\\Http\\Controllers\\StaffCategoryController@destroy');
    // User
    Route::get('/users', 'App\\Http\\Controllers\\StaffUserController@index');
    Route::get('/users/create', 'App\\Http\\Controllers\\StaffUserController@create');
    Route::post('/users', 'App\\Http\\Controllers\\StaffUserController@store');
    Route::get('/users/{id}/edit', 'App\\Http\\Controllers\\StaffUserController@edit');
    Route::post('/users/{id}/update', 'App\\Http\\Controllers\\StaffUserController@update');
    Route::post('/users/{id}/delete', 'App\\Http\\Controllers\\StaffUserController@destroy');
    // Borrowing
    Route::get('/borrowings', 'App\\Http\\Controllers\\StaffBorrowingController@index');
    Route::post('/borrowings/{id}/return', 'App\\Http\\Controllers\\StaffBorrowingController@return');
    // Review
    Route::get('/reviews', 'App\\Http\\Controllers\\StaffReviewController@index');
    Route::post('/reviews/{id}/delete', 'App\\Http\\Controllers\\StaffReviewController@destroy');
});
