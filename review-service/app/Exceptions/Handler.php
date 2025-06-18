<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            // Provide clear error message when rate limit exceeded
            return response()->json([
                'message' => 'Rate limit exceeded. Please try again later.',
                'retry_after_seconds' => $e->getHeaders()['Retry-After'] ?? 60,
            ], 429);
        });
    }
}