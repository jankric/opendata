<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateJsonMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $contentType = $request->header('Content-Type');
            
            if (str_contains($contentType, 'application/json')) {
                $content = $request->getContent();
                
                if (!empty($content)) {
                    json_decode($content);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid JSON format',
                            'error' => json_last_error_msg(),
                        ], 400);
                    }
                }
            }
        }
        
        return $next($request);
    }
}