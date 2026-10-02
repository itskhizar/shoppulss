<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Super Admin always bypasses all role restrictions
        if ($user->hasRole('Super Admin', 'super-admin')) {
            return $next($request);
        }

        if (! empty($roles) && $user->hasRole(...$roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Your account role does not have permission to access this area.');
    }
}
