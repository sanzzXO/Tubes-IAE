<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BookCatalogService
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
                        'Authorization' => 'Bearer ' . $this->token,
                        'User-Agent' => 'Review-Service/1.0',
                        'X-Service-Name' => 'review-service'
                    ])
                    ->get("{$this->baseUrl}/books/{$bookId}");

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("Book data retrieved successfully", ['book_id' => $bookId]);
                    return $data['data'] ?? $data;
                }
                
                Log::warning("Failed to fetch book", [
                    'book_id' => $bookId,
                    'status' => $response->status()
                ]);
                
                return null;
            } catch (\Exception $e) {
                Log::error('Error fetching book from book-catalog-service', [
                    'book_id' => $bookId,
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        });
    }

    /**
     * Check if book exists
     */
    public function bookExists($bookId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Review-Service/1.0',
                    'X-Service-Name' => 'review-service'
                ])
                ->get("{$this->baseUrl}/books/{$bookId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error checking book existence', [
                'book_id' => $bookId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get book statistics
     */
    public function getBookStats($bookId)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Review-Service/1.0',
                    'X-Service-Name' => 'review-service'
                ])
                ->get("{$this->baseUrl}/books/{$bookId}/stats");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching book stats', [
                'book_id' => $bookId,
                'error' => $e->getMessage()
            ]);
            return [];
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
                    'User-Agent' => 'Review-Service/1.0',
                    'X-Service-Name' => 'review-service'
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