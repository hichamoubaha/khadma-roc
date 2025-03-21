<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the authenticated user's role matches the required role
        if ($request->user()->role !== $role) {
            return response()->json(['message' => 'Access forbidden.'], 403); // Access forbidden for non-admin users
        }

        return $next($request); // Continue if user has correct role
    }
}
