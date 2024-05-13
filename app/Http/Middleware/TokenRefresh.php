<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenRefresh
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        updateTokenUsed();
        return $response;
    }
}
