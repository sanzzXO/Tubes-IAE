<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.user_auth.url');
    }

    public function getUser($userId)
    {
        $cacheKey = "user_detail_{$userId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->getServiceToken()
                    ])
                    ->get("{$this->baseUrl}/users/{$userId}");

                if ($response->successful()) {
                    return $response->json()['data'];
                }
                
                return null;
            } catch (\Exception $e) {
                \Log::error('Error fetching user: ' . $e->getMessage());
                return null;
            }
        });
    }

    private function getServiceToken()
    {
        // Implementasi untuk mendapatkan service-to-service token
        return config('services.user_auth.token');
    }
}