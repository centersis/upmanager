<?php

namespace App\Domains\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActiveApi
{
    /**
     * Handle an incoming request for API endpoints.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sua conta está inativa. Entre em contato com o administrador.',
            ], 403);
        }

        return $next($request);
    }
}

