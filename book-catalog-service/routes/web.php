<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Tampilan awal langsung ke katalog
Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/katalog', [BookController::class, 'index'])->name('books.index');

// Books Routes
Route::resource('books', BookController::class);

// Categories Routes  
Route::resource('categories', CategoryController::class);

// Dashboard/Home untuk menu navigasi
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
