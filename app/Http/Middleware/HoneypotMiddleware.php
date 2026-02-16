<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST')) {
            if ($request->filled('my_hp_field')) {
                // Return a generic error or just ignore the request
                return response()->json(['message' => 'Spam detected.'], 422);
            }
        }

        return $next($request);
    }
}
