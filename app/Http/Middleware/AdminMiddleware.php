<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please log in with an administrative account.');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
