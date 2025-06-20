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
