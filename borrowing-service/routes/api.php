<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BorrowingController;
use App\Services\BookService;
use App\Services\UserService;

/*
|--------------------------------------------------------------------------
| API Routes for Borrowing Service
|--------------------------------------------------------------------------
|
| Here you can register API routes for the borrowing service.
| These routes can be tested using Postman or any API testing tool.
|
*/

// Remove authentication middleware for testing purposes
// You can add it back later: Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

// GET Routes
Route::get('/borrowings', [BorrowingController::class, 'index']); // Get all borrowings
Route::get('/borrowings/{id}', [BorrowingController::class, 'show']); // Get specific borrowing

// POST Routes
Route::post('/borrowings', [BorrowingController::class, 'store']); // Create new borrowing
Route::post('/borrowings/{id}/return', [BorrowingController::class, 'returnBook']); // Return book
Route::post('/borrowings/{id}/extend', [BorrowingController::class, 'extend']); // Extend borrowing

// Health Check Route
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'service' => 'Borrowing Service',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// Test connection to book-catalog-service
Route::get('/test-book-service', function (BookService $bookService) {
    $isConnected = $bookService->testConnection();
    
    return response()->json([
        'service' => 'Borrowing Service',
        'book_catalog_service_connected' => $isConnected,
        'book_catalog_service_url' => config('services.book_catalog.url', 'http://localhost:8001/api'),
        'timestamp' => now()->toISOString(),
        'message' => $isConnected ? 'Successfully connected to book-catalog-service' : 'Failed to connect to book-catalog-service'
    ]);
});

// Test connection to auth-service
Route::get('/test-auth-service', function (UserService $userService) {
    $isConnected = $userService->testConnection();
    
    return response()->json([
        'service' => 'Borrowing Service',
        'auth_service_connected' => $isConnected,
        'auth_service_url' => config('services.auth.url', 'http://localhost:8000/api'),
        'timestamp' => now()->toISOString(),
        'message' => $isConnected ? 'Successfully connected to auth-service' : 'Failed to connect to auth-service'
    ]);
});

// Test book retrieval
Route::get('/test-book/{id}', function (BookService $bookService, $id) {
    $book = $bookService->getBook($id);
    $availability = $bookService->getBookAvailability($id);
    
    return response()->json([
        'book_id' => $id,
        'book_found' => $book !== null,
        'book_data' => $book,
        'availability' => $availability,
        'timestamp' => now()->toISOString()
    ]);
});

// Test user retrieval
Route::get('/test-user/{id}', function (UserService $userService, $id) {
    $user = $userService->getUser($id);
    $isActive = $userService->isUserActive($id);
    
    return response()->json([
        'id' => $id,
        'user_found' => $user !== null,
        'user_data' => $user,
        'is_active' => $isActive,
        'timestamp' => now()->toISOString()
    ]);
});

// Test service integration
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

// API Documentation Route
Route::get('/', function () {
    return response()->json([
        'message' => 'Borrowing Service API',
        'version' => '1.0.0',
        'endpoints' => [
            'GET /borrowings' => 'Get all borrowings',
            'GET /borrowings/{id}' => 'Get specific borrowing',
            'POST /borrowings' => 'Create new borrowing',
            'POST /borrowings/{id}/return' => 'Return book',
            'POST /borrowings/{id}/extend' => 'Extend borrowing',
            'GET /health' => 'Health check',
            'GET /test-book-service' => 'Test connection to book-catalog-service',
            'GET /test-auth-service' => 'Test connection to auth-service',
            'GET /test-book/{id}' => 'Test book retrieval from book-catalog-service',
            'GET /test-user/{id}' => 'Test user retrieval from auth-service',
            'GET /test-integration' => 'Test all service integrations',
            'GET /' => 'API documentation'
        ],
        'example_requests' => [
            'create_borrowing' => [
                'method' => 'POST',
                'url' => '/api/borrowings',
                'body' => [
                    'user_id' => 1,
                    'book_id' => 1,
                    'borrowed_date' => '2024-01-15',
                    'loan_period_days' => 14
                ]
            ],
            'extend_borrowing' => [
                'method' => 'POST',
                'url' => '/api/borrowings/1/extend',
                'body' => [
                    'extend_days' => 7
                ]
            ],
            'return_book' => [
                'method' => 'POST',
                'url' => '/api/borrowings/1/return',
                'body' => [
                    'returned_date' => '2024-01-20'
                ]
            ]
        ],
        'microservices' => [
            'book_catalog_service' => [
                'url' => config('services.book_catalog.url', 'http://localhost:8001/api'),
                'status' => 'Check with /test-book-service endpoint'
            ],
            'auth_service' => [
                'url' => config('services.auth.url', 'http://localhost:8000/api'),
                'status' => 'Check with /test-auth-service endpoint'
            ]
        ]
    ]);
});

// }); // End of authentication middleware group