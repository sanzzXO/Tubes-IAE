<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StaffController;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Staff routes
Route::middleware(['auth'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])
        ->middleware(\App\Http\Middleware\CheckRole::class.':staff')
        ->name('staff.dashboard');
});

// Regular user dashboard
Route::get('/dashboard', function () {
    if (auth()->user()->isStaff()) {
        return redirect()->route('staff.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
