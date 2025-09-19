<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah terautentikasi dan tidak null
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated. Please login first.',
                'error' => 'TOKEN_MISSING'
            ], 401);
        }

        // Cek apakah user memiliki role admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden. Admin access required.',
                'error' => 'INSUFFICIENT_PRIVILEGES',
                'your_role' => $request->user()->role,
                'required_role' => 'admin'
            ], 403);
        }

        // Jika user adalah admin, lanjutkan request
        return $next($request);
    }
}