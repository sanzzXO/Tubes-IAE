<?php

use Illuminate\Support\Facades\Route;
use App\Services\BookService;
use App\Services\UserService;

Route::get('/', function () {
    return view('welcome');
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'service' => 'Borrowing Service',
        'status' => 'healthy',
        'timestamp' => now()->toISOString()
    ]);
});

// Test integration with other services
Route::get('/test-integration', function (BookService $bookService, UserService $userService) {
    $bookServiceConnected = $bookService->testConnection();
    $authServiceConnected = $userService->testConnection();
    
    return response()->json([
        'service' => 'Borrowing Service',
        'integrations' => [
            'book_catalog_service' => [
                'connected' => $bookServiceConnected,
                'url' => config('services.book_catalog.url', 'http://localhost:8001/api'),
                'status' => $bookServiceConnected ? 'OK' : 'FAILED'
            ],
            'auth_service' => [
                'connected' => $authServiceConnected,
                'url' => config('services.auth.url', 'http://localhost:8000/api'),
                'status' => $authServiceConnected ? 'OK' : 'FAILED'
            ]
        ],
        'overall_status' => ($bookServiceConnected && $authServiceConnected) ? 'HEALTHY' : 'DEGRADED',
        'timestamp' => now()->toISOString()
    ]);
});
