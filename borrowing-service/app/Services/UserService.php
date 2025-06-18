<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UserService
{
    protected $baseUrl;
    protected $timeout;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.auth.url', 'http://localhost:8000/api');
        $this->timeout = config('services.auth.timeout', 10);
        $this->token = config('services.auth.token', 'auth-service-token');
    }

    /**
     * Get user details from auth-service
     */
    public function getUser($userId)
    {
        $cacheKey = "user_detail_{$userId}";
        
        return Cache::remember($cacheKey, 1800, function () use ($userId) {
            try {
                Log::info("Fetching user details for ID: {$userId}");
                
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token,
                        'User-Agent' => 'Borrowing-Service/1.0',
                        'X-Service-Name' => 'borrowing-service'
                    ])
                    ->get("{$this->baseUrl}/users/{$userId}");

                Log::info("Auth service response status: " . $response->status());

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("User data retrieved successfully", ['user_id' => $userId]);
                    return $data['data'] ?? $data;
                }
                
                Log::warning("Failed to fetch user", [
                    'user_id' => $userId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                
                return null;
            } catch (\Exception $e) {
                Log::error('Error fetching user from auth-service', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                    'url' => "{$this->baseUrl}/users/{$userId}"
                ]);
                return null;
            }
        });
    }

    /**
     * Validate user token
     */
    public function validateToken($token)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->post("{$this->baseUrl}/validate-token", [
                    'token' => $token
                ]);

            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error validating token', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'Borrowing-Service/1.0',
                    'X-Service-Name' => 'borrowing-service'
                ])
                ->get("{$this->baseUrl}/users/email/{$email}");

            if ($response->successful()) {
                return $response->json()['data'] ?? $response->json();
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching user by email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Check if user exists and is active
     */
    public function isUserActive($userId)
    {
        $user = $this->getUser($userId);
        
        if (!$user) {
            return false;
        }

        // Check if user is active (assuming there's an 'active' or 'status' field)
        $status = $user['status'] ?? $user['active'] ?? 'active';
        return $status === 'active' || $status === 1;
    }

    /**
     * Test connection to auth-service
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
            Log::error('Error testing connection to auth-service', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}