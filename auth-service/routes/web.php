<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StaffController;
use App\Services\BookCatalogService;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'Auth Service',
        'status' => 'healthy',
        'timestamp' => now()->toISOString()
    ]);
});

// Test integration with other services
Route::get('/test-integration', function (BookCatalogService $bookService) {
    $bookServiceConnected = $bookService->testConnection();
    
    return response()->json([
        'service' => 'Auth Service',
        'integrations' => [
            'book_catalog_service' => [
                'connected' => $bookSe` `````````````````````````````   `   rviceConnected,
                'url' => config('services.book_catalog.url', 'http://localhost:8001/api'),
                'status' => $bookServiceConnected ? 'OK' : 'FAILED'
            ]
        ],
        'overall_status' => $bookServiceConnected ? 'HEALTHY' : 'DEGRADED',
        'timestamp' => now()->toISOString()
    ]);
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
