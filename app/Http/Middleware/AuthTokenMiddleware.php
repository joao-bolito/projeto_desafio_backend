<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token || !User::where('api_token', $token)->exists()) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }

        return $next($request);
    }
}
