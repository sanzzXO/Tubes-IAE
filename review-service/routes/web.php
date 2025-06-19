<?php

use Illuminate\Support\Facades\Route;
use App\Services\AuthService;
use App\Services\BookCatalogService;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'Review Service',
        'status' => 'healthy',
        'timestamp' => now()->toISOString()
    ]);
});

// Test integration with other services
Route::get('/test-integration', function (AuthService $authService, BookCatalogService $bookService) {
    $authServiceConnected = $authService->testConnection();
    $bookServiceConnected = $bookService->testConnection();
    
    return response()->json([
        'service' => 'Review Service',
        'integrations' => [
            'auth_service' => [
                'connected' => $authServiceConnected,
                'url' => config('services.auth.url', 'http://localhost:8000/api'),
                'status' => $authServiceConnected ? 'OK' : 'FAILED'
            ],
            'book_catalog_service' => [
                'connected' => $bookServiceConnected,
                'url' => config('services.book_catalog.url', 'http://localhost:8001/api'),
                'status' => $bookServiceConnected ? 'OK' : 'FAILED'
            ]
        ],
        'overall_status' => ($authServiceConnected && $bookServiceConnected) ? 'HEALTHY' : 'DEGRADED',
        'timestamp' => now()->toISOString()
    ]);
});
