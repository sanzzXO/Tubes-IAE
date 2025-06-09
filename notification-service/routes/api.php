<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

// Notification endpoints
Route::post('/notifications/borrowing', [NotificationController::class, 'sendBorrowingNotification']);
Route::post('/notifications/reminder', [NotificationController::class, 'sendReminderNotification']);
Route::post('/notifications/fine', [NotificationController::class, 'sendFineNotification']);
Route::post('/notifications/availability', [NotificationController::class, 'sendAvailabilityNotification']);

// Get notifications
Route::get('/notifications/user/{userId}', [NotificationController::class, 'getUserNotifications']);
Route::get('/notifications', [NotificationController::class, 'getAllNotifications']);