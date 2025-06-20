<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Services\AuthService;

// Tampilan awal langsung ke katalog
Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/katalog', [BookController::class, 'index'])->name('books.index');

// Books Routes
Route::resource('books', BookController::class);

// Categories Routes  
Route::resource('categories', CategoryController::class);

// Dashboard/Home untuk menu navigasi
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'Book Catalog Service',
        'status' => 'healthy',
        'timestamp' => now()->toISOString()
    ]);
});

// Test integration with other services
Route::get('/test-integration', function (AuthService $authService) {
    $authServiceConnected = $authService->testConnection();
    
    return response()->json([
        'service' => 'Book Catalog Service',
        'integrations' => [
            'auth_service' => [
                'connected' => $authServiceConnected,
                'url' => config('services.auth.url', 'http://localhost:8000/api'),
                'status' => $authServiceConnected ? 'OK' : 'FAILED'
            ]
        ],
        'overall_status' => $authServiceConnected ? 'HEALTHY' : 'DEGRADED',
        'timestamp' => now()->toISOString()
    ]);
});
