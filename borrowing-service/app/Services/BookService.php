<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BookService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.book_catalog.url');
    }

    public function getBook($bookId)
    {
        $cacheKey = "book_detail_{$bookId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($bookId) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->getServiceToken()
                    ])
                    ->get("{$this->baseUrl}/books/{$bookId}");

                if ($response->successful()) {
                    return $response->json()['data'];
                }
                
                return null;
            } catch (\Exception $e) {
                \Log::error('Error fetching book: ' . $e->getMessage());
                return null;
            }
        });
    }

    public function decreaseStock($bookId, $quantity = 1)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->getServiceToken()
                ])
                ->put("{$this->baseUrl}/books/{$bookId}/decrease-stock", [
                    'quantity' => $quantity
                ]);

            // Clear cache
            Cache::forget("book_detail_{$bookId}");
            
            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Error decreasing book stock: ' . $e->getMessage());
            return false;
        }
    }

    public function increaseStock($bookId, $quantity = 1)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->getServiceToken()
                ])
                ->put("{$this->baseUrl}/books/{$bookId}/increase-stock", [
                    'quantity' => $quantity
                ]);

            // Clear cache
            Cache::forget("book_detail_{$bookId}");
            
            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Error increasing book stock: ' . $e->getMessage());
            return false;
        }
    }

    private function getServiceToken()
    {
        // Implementasi untuk mendapatkan service-to-service token
        return config('services.book_catalog.token');
    }
}