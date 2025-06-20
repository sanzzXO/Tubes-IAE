<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/books', 'App\Http\Controllers\BookController@index');
Route::get('/books/{id}', 'App\Http\Controllers\BookController@show');

Route::get('/borrowings', 'App\Http\Controllers\BorrowingController@index');

Route::get('/reviews', 'App\Http\Controllers\ReviewController@index');
Route::get('/reviews/create/{bookId}', 'App\Http\Controllers\ReviewController@create');

Route::get('/dashboard', 'App\Http\Controllers\UserController@dashboard');
Route::get('/staff/dashboard', 'App\Http\Controllers\StaffController@dashboard');

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/reviews', 'App\Http\Controllers\ReviewController@store');
Route::post('/borrowings', 'App\Http\Controllers\BorrowingController@store');
Route::post('/borrowings/return/{id}', 'App\Http\Controllers\BorrowingController@return');
Route::post('/logout', 'App\Http\Controllers\AuthController@logout');

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
