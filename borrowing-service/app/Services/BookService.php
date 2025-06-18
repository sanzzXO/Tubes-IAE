<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BookService
{
    protected $baseUrl;
    protected $timeout;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.book_catalog.url', 'http://localhost:8001/api');
        $this->timeout = config('services.book_catalog.timeout', 15);
        $this->token = config('services.book_catalog.token', 'book-catalog-service-token');
    }

    /**
     * Get book details from book-catalog-service
     */
    public function getBook($bookId)
    {
        $cacheKey = "book_detail_{$bookId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($bookId) {
            try {
                Log::info("Fetching book details for ID: {$bookId}");
                
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token,
                        'User-Agent' => 'Borrowing-Service/1.0',
                        'X-Service-Name' => 'borrowing-service'
                    ])
                    ->get("{$this->baseUrl}/books/{$bookId}");

                Log::info("Book service response status: " . $response->status());

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Book data retrieved successfully", ['book_id' => $bookId]);
                    return $data['data'] ?? $data;
                }
                
                Log::warning("Failed to fetch book", [
                    'book_id' => $bookId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return null;
            } catch (\Exception $e) {
                Log::error('Error fetching book from book-catalog-service', [
                    'book_id' => $bookId,
                    'error' => $e->getMessage(),
                    'url' => "{$this->baseUrl}/books/{$bookId}"
                ]);
                return null;
            }
        });
    }

    /**
     * Check if book is available for borrowing
     */
    public function isBookAvailable($bookId)
    {
        $book = $this->getBook($bookId);
        
        if (!$book) {
            return false;
        }

        // Check different possible field names for availability
        $available = $book['available'] ?? $book['available_copies'] ?? $book['stock'] ?? 0;
        return $available > 0;
    }

    /**
     * Get book availability count
     */
    public function getBookAvailability($bookId)
    {
        $book = $this->getBook($bookId);
        
        if (!$book) {
            return 0;
        }

        return $book['available'] ?? $book['available_copies'] ?? $book['stock'] ?? 0;
    }

    /**
     * Decrease book stock when borrowed
     */
    public function decreaseStock($bookId, $quantity = 1)
    {
        try {
            Log::info("Decreasing stock for book", ['book_id' => $bookId, 'quantity' => $quantity]);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->put("{$this->baseUrl}/books/{$bookId}", [
                    'stock' => -$quantity // Decrease stock
                ]);

            // Clear cache
            Cache::forget("book_detail_{$bookId}");
            
            if ($response->successful()) {
                Log::info("Stock decreased successfully", ['book_id' => $bookId]);
                return true;
            }
            
            Log::warning("Failed to decrease stock", [
                'book_id' => $bookId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error decreasing book stock', [
                'book_id' => $bookId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Increase book stock when returned
     */
    public function increaseStock($bookId, $quantity = 1)
    {
        try {
            Log::info("Increasing stock for book", ['book_id' => $bookId, 'quantity' => $quantity]);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->put("{$this->baseUrl}/books/{$bookId}", [
                    'stock' => $quantity // Increase stock
                ]);

            // Clear cache
            Cache::forget("book_detail_{$bookId}");
            
            if ($response->successful()) {
                Log::info("Stock increased successfully", ['book_id' => $bookId]);
                return true;
            }
            
            Log::warning("Failed to increase stock", [
                'book_id' => $bookId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error increasing book stock', [
                'book_id' => $bookId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update book status
     */
    public function updateBookStatus($bookId, $status)
    {
        try {
            Log::info("Updating book status", ['book_id' => $bookId, 'status' => $status]);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->put("{$this->baseUrl}/books/{$bookId}", [
                    'status' => $status
                ]);

            // Clear cache
            Cache::forget("book_detail_{$bookId}");
            
            if ($response->successful()) {
                Log::info("Book status updated successfully", ['book_id' => $bookId, 'status' => $status]);
                return true;
            }
            
            Log::warning("Failed to update book status", [
                'book_id' => $bookId,
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Error updating book status', [
                'book_id' => $bookId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Search books
     */
    public function searchBooks($query, $filters = [])
    {
        try {
            $params = array_merge(['q' => $query], $filters);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->get("{$this->baseUrl}/books", $params);

            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error searching books', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Test connection to book-catalog-service
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->get("{$this->baseUrl}/health");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error testing connection to book-catalog-service', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}