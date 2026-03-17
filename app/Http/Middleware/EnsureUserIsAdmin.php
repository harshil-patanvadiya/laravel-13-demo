<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole('admin')) {
            return response()->json([
                'message' => 'Forbidden. Admin access only.',
            ], 403);
        }

        return $next($request);
    }
}

