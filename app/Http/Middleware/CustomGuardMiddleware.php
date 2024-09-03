<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CustomGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        
        // if() {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'You are not unauthorized to access this.',
        //     ], Response::HTTP_UNAUTHORIZED);
        // }
    }
}
