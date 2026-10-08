<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRedirects
{
    /**
     * Handle an incoming request and check if a 301 redirect is configured.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = trim($request->path(), '/');

        if (! empty($path)) {
            $redirect = Redirect::active()
                ->where(function ($q) use ($path) {
                    $q->where('source_path', $path)
                        ->orWhere('source_path', '/'.$path);
                })
                ->first();

            if ($redirect) {
                return redirect($redirect->target_path, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
