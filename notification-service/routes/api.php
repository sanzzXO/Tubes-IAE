<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/notify', [NotificationController::class, 'send']);
Route::get('/notify/{user_id}', [NotificationController::class, 'getUserNotifications']);