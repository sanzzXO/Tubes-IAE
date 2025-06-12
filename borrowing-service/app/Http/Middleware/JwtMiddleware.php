<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(config('app.jwt_secret'), 'HS256'));
            $request->user_data = $decoded;
            
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }
}
