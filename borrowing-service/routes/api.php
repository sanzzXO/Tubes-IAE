<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BorrowingController;

Route::get('/borrowings', [BorrowingController::class, 'getAllBorrowings']);
Route::post('/borrowings', [BorrowingController::class, 'createBorrowing']);
Route::post('/borrowings/{id}/return', [BorrowingController::class, 'returnBook']);
Route::get('/borrowings/check', [BorrowingController::class, 'checkBookStatus']);
Route::get('/borrowings/{id}', [BorrowingController::class, 'getBorrowingDetails']);
Route::get('/borrowed-books', [BorrowingController::class, 'getBorrowedBooks']); 