<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowingController;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // // Borrowing routes
    // Route::apiResource('borrowings', BorrowingController::class);
    // Route::post('borrowings/{id}/return', [BorrowingController::class, 'returnBook']);
    // Route::post('borrowings/{id}/extend', [BorrowingController::class, 'extend']);
    // Route::get('users/{userId}/borrowings', [BorrowingController::class, 'userHistory']);
    // Route::get('borrowings-statistics', [BorrowingController::class, 'statistics']);
    Route::post('/borrowings', [BorrowingController::class, 'borrow']);
    Route::post('/borrowings/return/{id}', [BorrowingController::class, 'return']);
    Route::post('/borrowings/extend/{id}', [BorrowingController::class, 'extend']);

});