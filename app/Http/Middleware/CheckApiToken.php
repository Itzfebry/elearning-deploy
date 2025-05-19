<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Token tidak ditemukan.'], 401);
        }

        $accessToken = str_replace('Bearer ', '', $header);

        $token = PersonalAccessToken::findToken($accessToken);

        if (!$token) {
            return response()->json(['message' => 'Token tidak valid.'], 401);
        }

        // Set user yang sedang login (penting)
        $request->merge(['user' => $token->tokenable]);

        return $next($request);
    }
}
